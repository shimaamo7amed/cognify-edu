<?php

namespace App\Services;

use App\Models\CognifyParent;
use App\Models\CognifyChild;
use App\Models\ObservationSession;
use App\Models\ObservationChildCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Nafezly\Payments\Classes\FawryPayment;

class ObservationChildCaseService
{
    protected $fawry_url;
    protected $fawry_merchant;
    protected $fawry_secret;

    public function __construct()
    {
        $this->fawry_url = config('nafezly-payments.FAWRY_URL');
        $this->fawry_merchant = config('nafezly-payments.FAWRY_MERCHANT');
        $this->fawry_secret = config('nafezly-payments.FAWRY_SECRET');
    }

    public function bookWithFawry(array $data)
    {
        $parent = Auth::user();
        if (!$parent) {
            return ['status' => false, 'message' => 'User not authenticated'];
        }

        $child = CognifyChild::where('parent_id', $parent->id)
            ->where('id', $data['child_id'])
            ->first();
        if (!$child) {
            return ['status' => false, 'message' => __('messages.child_not_found')];
        }

        $session = ObservationSession::find($data['session_id']);
        if (!$session) {
            return ['status' => false, 'message' => __('messages.session_not_found')];
        }

        $normalizedTime = str_replace(['ص', 'م'], ['AM', 'PM'], $data['time']);
        try {
            $time = Carbon::parse($normalizedTime)->format('H:i:s');
        } catch (\Exception $e) {
            return ['status' => false, 'message' => 'Invalid time format'];
        }

        $slot = $session->slots()
            ->whereDate('start_time', $data['date'])
            ->whereTime('start_time', '<=', $time)
            ->whereTime('end_time', '>=', $time)
            ->first();

        if (!$slot) {
            return ['status' => false, 'message' => __('messages.slot_not_available')];
        }

        if ($this->hasAlreadyBooked($data['child_id'], $data['session_id'])) {
            return ['status' => false, 'message' => __('messages.session_already_booked')];
        }

        $totalAmount = $session->service_fee + $session->service_tax;
        $fawry = new FawryPayment();
        $paymentMethod = $fawry->pay(
            amount: $totalAmount,
            user_id: $parent->id,
            user_first_name: $parent->name,
            user_last_name: $parent->name,
            user_email: $parent->email,
            user_phone: $parent->phone,
            source: 'BOOKING_' . $data['session_id'] . '_' . time(),
        );

        $normalized = $paymentMethod;
        if (is_array($paymentMethod)) {
            if (array_is_list($paymentMethod) && isset($paymentMethod[0]) && is_array($paymentMethod[0])) {
                $normalized = $paymentMethod[0];
            } elseif (isset($paymentMethod['payment']['payment']) && is_array($paymentMethod['payment']['payment'])) {
                $normalized = $paymentMethod['payment']['payment'];
            } elseif (isset($paymentMethod['payment']) && is_array($paymentMethod['payment'])) {
                $normalized = $paymentMethod['payment'];
            }
        }

        $paymentId = $normalized['payment_id'] ?? null;
        $html = $normalized['html'] ?? '';

        if (empty($paymentId) && isset($paymentMethod['payment_id'])) {
            $paymentId = $paymentMethod['payment_id'];
        }

        if (empty($html)) {
            $html = $paymentMethod['payment']['payment']['html']
                ?? $paymentMethod['payment']['html']
                ?? $paymentMethod['html']
                ?? '';
        }

        if (empty($paymentId) || empty($html)) {
            return ['status' => false, 'message' => 'Payment initialization failed'];
        }

        Cache::put('fawry_case_' . $paymentId, [
            'child_id' => $child->id,
            'session_id' => $session->id,
            'slot_date' => $data['date'],
            'slot_time' => $time,
            'total_amount' => $totalAmount,
        ], 3600);
        $returnUrl = route('verify-payment', ['payment' => 'fawry']) . '?type=observation';

        $signature = hash(
            'sha256',
            $this->fawry_merchant .
                $paymentId .
                $parent->id .
                $returnUrl .
                "1" .
                "1" .
                number_format($totalAmount, 2, '.', '') .
                $this->fawry_secret
        );

        $paymentData = [
            'payment_id' => $paymentId,
            'user_name' => $parent->name,
            'user_email' => $parent->email,
            'user_phone' => $parent->phone,
            'user_id' => $parent->id,
            'amount' => $totalAmount,
            'description' => 'Booking Session #' . $data['session_id'],
            'signature' => $signature,
        ];

        $cacheKey = 'fawry_payment_' . $paymentId;
        Cache::put($cacheKey, $paymentData, 3600);

        return [
            'status' => true,
            'payment_id' => $paymentId,
            'redirect_url' => route('fawry.payment.page') . '?token=' . $paymentId . '&type=observation',
            'message' => __('messages.proceed_to_payment'),
            'role' => $parent->role,
            'step' => $parent->step,
            'token' => $parent->createToken('auth_token')->plainTextToken,
        ];
    }

    public function verifyFawry($data): array
    {
        try {
            $fawry = new FawryPayment();
            if (is_array($data)) {
                $request = new Request();
                $request->merge($data);
                if (isset($data['payment_id']) && !isset($data['chargeResponse'])) {
                    $request->merge([
                        'chargeResponse' => json_encode([
                            'merchantRefNumber' => $data['payment_id']
                        ])
                    ]);
                }
            } else {
                $request = $data;
            }

            Log::info('Fawry verification request details', [
                'request_data' => is_array($data) ? $data : $data->all(),
                'request_object' => $request instanceof Request ? [
                    'all' => $request->all(),
                    'has_charge_response' => $request->has('chargeResponse'),
                    'charge_response' => $request->input('chargeResponse')
                ] : 'Not a Request object'
            ]);

            try {
                $verify = $fawry->verify($request);
                Log::info('Fawry verification response', [
                    'response' => $verify
                ]);
            } catch (\Exception $e) {
                Log::error('Exception in Fawry verify method', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);

                try {
                    $reference_id = null;
                    if ($request->has('chargeResponse')) {
                        $res = json_decode($request->input('chargeResponse'), true);
                        if (is_array($res) && isset($res['merchantRefNumber'])) {
                            $reference_id = $res['merchantRefNumber'];
                        }
                    } elseif ($request->has('payment_id')) {
                        $reference_id = $request->input('payment_id');
                    } elseif (is_array($data) && isset($data['payment_id'])) {
                        $reference_id = $data['payment_id'];
                    }

                    if ($reference_id) {
                        $hash = hash('sha256', $this->fawry_merchant . $reference_id . $this->fawry_secret);
                        $url = $this->fawry_url . 'ECommerceWeb/Fawry/payments/status/v2?merchantCode=' .
                            $this->fawry_merchant . '&merchantRefNumber=' . $reference_id . '&signature=' . $hash;

                        Log::info('Manually calling Fawry status API', [
                            'url' => $url,
                            'reference_id' => $reference_id
                        ]);

                        $response = \Illuminate\Support\Facades\Http::get($url);
                        Log::info('Raw Fawry API response', [
                            'status' => $response->status(),
                            'body' => $response->body(),
                            'json' => $response->json(),
                            'headers' => $response->headers()
                        ]);

                        if ($response->successful()) {
                            $responseData = $response->json();
                            Log::info('Parsed Fawry response data', [
                                'response_data' => $responseData
                            ]);

                            if (isset($responseData['orderStatus'])) {
                                $verify = [
                                    'success' => $responseData['orderStatus'] === 'PAID',
                                    'payment_id' => $reference_id,
                                    'message' => $responseData['orderStatus'] === 'PAID' ?
                                        'Payment successful' : 'Payment not completed: ' . $responseData['orderStatus'],
                                    'process_data' => $responseData
                                ];

                                Log::info('Payment verification result', [
                                    'success' => $responseData['orderStatus'] === 'PAID',
                                    'order_status' => $responseData['orderStatus'],
                                    'reference_id' => $reference_id
                                ]);
                            } else {
                                $verify = [
                                    'success' => false,
                                    'payment_id' => $reference_id,
                                    'message' => 'Invalid response from Fawry API - missing orderStatus',
                                    'process_data' => $responseData
                                ];
                            }
                        } else {
                            $verify = [
                                'success' => false,
                                'payment_id' => $reference_id,
                                'message' => 'Failed to connect to Fawry API: ' . $response->status(),
                                'process_data' => $response->body()
                            ];
                        }
                    } else {
                        $verify = [
                            'success' => false,
                            'message' => 'Could not determine reference ID for verification'
                        ];
                    }
                } catch (\Exception $innerEx) {
                    Log::error('Exception in manual Fawry verification', [
                        'error' => $innerEx->getMessage(),
                        'trace' => $innerEx->getTraceAsString()
                    ]);

                    throw $e;
                }
            }

            Log::info('Final verification result', [
                'verify' => $verify
            ]);

            if (!isset($verify['success']) || $verify['success'] !== true) {
                return [
                    'status' => false,
                    'message' => isset($verify['message']) ? $verify['message'] : 'Payment verification failed',
                    'details' => $verify
                ];
            }

            Log::info('Payment verified successfully', [
                'payment_id' => $verify['payment_id'] ?? null,
                'reference_id' => $reference_id ?? null
            ]);

            // Extract real transaction number from verification data when available
            $transactionNumber = null;
            if (isset($verify['fawryRefNumber']) && $verify['fawryRefNumber']) {
                $transactionNumber = $verify['fawryRefNumber'];
            } elseif (isset($verify['process_data']['fawryRefNumber']) && $verify['process_data']['fawryRefNumber']) {
                $transactionNumber = $verify['process_data']['fawryRefNumber'];
            } elseif (isset($verify['process_data']['referenceNumber']) && $verify['process_data']['referenceNumber']) {
                $transactionNumber = $verify['process_data']['referenceNumber'];
            } elseif (isset($verify['process_data']['orderTransactions'][0]['referenceNumber'])) {
                $transactionNumber = $verify['process_data']['orderTransactions'][0]['referenceNumber'];
            } elseif (isset($verify['details']['process_data']['orderTransactions'][0]['referenceNumber'])) {
                $transactionNumber = $verify['details']['process_data']['orderTransactions'][0]['referenceNumber'];
            }

            // Extract payment method from verification data when available (e.g., CARD, PayUsingCC)
            $paymentMethod = null;
            if (isset($verify['process_data'])) {
                $pd = $verify['process_data'];
                $paymentMethod =
                    ($pd['paymentMethod'] ?? null)
                    ?? ($pd['paymentMethodName'] ?? null)
                    ?? ($pd['orderTransactions'][0]['paymentMethod'] ?? null)
                    ?? ($pd['orderTransactions'][0]['paymentMethodName'] ?? null);
            }
            // Additional fallbacks: top-level or nested details, or incoming request payload
            if (!$paymentMethod) {
                $paymentMethod =
                    ($verify['paymentMethod'] ?? null)
                    ?? ($verify['paymentMethodName'] ?? null)
                    ?? ($verify['details']['process_data']['paymentMethod'] ?? null)
                    ?? ($verify['details']['process_data']['paymentMethodName'] ?? null)
                    ?? ($verify['details']['paymentMethod'] ?? null)
                    ?? ($verify['details']['paymentMethodName'] ?? null);
            }
            if (!$paymentMethod) {
                if (is_array($data)) {
                    $paymentMethod = $data['paymentMethod'] ?? $data['paymentMethodName'] ?? null;
                } elseif ($data instanceof Request) {
                    $paymentMethod = $data->input('paymentMethod') ?? $data->input('paymentMethodName');
                }
            }
            // dd($paymentMethod);
            $paymentId = $verify['payment_id'] ?? (is_array($data) ? ($data['payment_id'] ?? null) : $data->input('payment_id'));

            if (!$paymentId) {
                return ['status' => false, 'message' => 'Payment ID not found in verification response'];
            }

            $cached = Cache::get('fawry_case_' . $paymentId);
            if (!$cached) {
                return ['status' => false, 'message' => 'Booking data not found for payment: ' . $paymentId];
            }

            $case = ObservationChildCase::create([
                'child_id' => $cached['child_id'],
                'observation_session_id' => $cached['session_id'],
                'slot_date' => $cached['slot_date'],
                'slot_time' => $cached['slot_time'],
                'total_amount' => $cached['total_amount'],
                'status' => 'new_request',
                'payment_method' => $paymentMethod,
            ]);

            $parent = CognifyParent::whereHas('children', function ($q) use ($cached) {
                $q->where('id', $cached['child_id']);
            })->first();


            $parent = CognifyParent::whereHas('children', function ($q) use ($cached) {
                $q->where('id', $cached['child_id']);
            })->first();

            if ($parent) {
                $parent->update(['step' => 4]);
            }



            Cache::forget('fawry_case_' . $paymentId);

            return [
                'status' => true,
                'case_id' => $case->id,
                'message' => __('messages.booking_success'),
                'payment_method' => $paymentMethod,
                'payment_id' => $paymentId,
                'fawryRefNumber' => $transactionNumber,
                'merchantRefNumber' => $paymentId,
                'process_data' => $verify['process_data'] ?? null,
            ];
        } catch (\Exception $e) {
            Log::error('Exception in verifyFawry', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => is_array($data) ? $data : $data->all()
            ]);

            return [
                'status' => false,
                'message' => 'An error occurred during payment verification: ' . $e->getMessage(),
            ];
        }
    }

    protected function hasAlreadyBooked(int $childId, int $sessionId): bool
    {
        return ObservationChildCase::where('child_id', $childId)
            ->where('observation_session_id', $sessionId)
            ->exists();
    }
}
