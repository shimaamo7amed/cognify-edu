<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Services\ObservationChildCaseService;
use App\Http\Requests\ObservationChildCaseRequest;
use Illuminate\Routing\Controller;

class ObservationChildCaseController extends Controller
{
    protected $bookingService;
    protected $userJson;
    protected $step;

    public function __construct(ObservationChildCaseService $bookingService)
    {
        $this->bookingService = $bookingService;
        $user = auth()->user();

        if ($user) {
            $userData = [
                'step' => $user->step ?? null,
                'token' => $user->createToken('api-token')->plainTextToken,
            ];

            $this->step = $user->step ?? null;
            $this->userJson = urlencode(json_encode($userData));
        }
    }

    public function bookAndPay(ObservationChildCaseRequest $request)
    {
        try {
            Log::info('Fawry payment request', ['data' => $request->validated()]);

            $payment = $this->bookingService->bookWithFawry($request->validated());

            Log::info('Fawry payment response', ['response' => $payment]);

            if (isset($payment['status']) && !$payment['status']) {
                return response()->json([
                    'status' => false,
                    'message' => $payment['message']
                ], 422);
            }

            return response()->json([
                'status' => true,
                'payment_id' => $payment['payment_id'],
                'redirect_url' => $payment['redirect_url'],
                'message' => $payment['message'],
                // 'role' => $payment['role'] ?? null,
                // 'step' => $payment['step'] ?? $this->step,
                // 'token' => $payment['token'] ?? null,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Exception in bookAndPay', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => false,
                'message' => 'An error occurred while processing your payment'
            ], 500);
        }
    }

    public function showFawryPaymentPage(Request $request)
    {
        try {
            $token = $request->query('token');

            if (!$token) {
                Log::error('Fawry payment page accessed without token');
                return view('payments.error', [
                    'message' => 'Invalid payment request',
                    'description' => 'Missing payment token'
                ]);
            }

            $cacheKey = 'fawry_payment_' . $token;
            $paymentData = Cache::get($cacheKey);

            if (!$paymentData) {
                Log::error('Fawry payment data not found in cache', ['token' => $token]);
                return redirect()->to('https://cognfiy.vercel.app/payment-failed?' . http_build_query([
                    'message' => 'Payment session expired or invalid',
                    'description' => 'Please try initiating the payment again.',
                    'step' => $this->step,
                    'payment_type' => 'observation'
                ]));
            }

            $paymentData['token'] = $token;
            $paymentData['redirect_url'] = route('fawry.payment.page', ['token' => $token]);
            $paymentData['message'] = __('messages.proceed_to_payment');

            return view('payments.fawry_custom', $paymentData);
        } catch (\Exception $e) {
            Log::error('Exception in showFawryPaymentPage', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->to('https://cognfiy.vercel.app/payment-failed?' . http_build_query([
                'message' => 'An error occurred while processing your payment',
                'description' => 'Please try again later or contact support.',
                'step' => $this->step,
                'payment_type' => 'observation'
            ]));
        }
    }

    public function payment_verify(Request $request)
    {
        Log::info('Payment verification request', [
            'all_params' => $request->all(),
            'path' => $request->path(),
            'payment' => $request->route('payment')
        ]);

        if ($request->has('description') || $request->has('errorId') || $request->has('reason')) {
            Log::error('Fawry payment error', $request->only('description', 'errorId', 'reason'));

            return redirect()->to('https://cognfiy.vercel.app/payment-failed?' . http_build_query([
                'success' => false,
                'message' => $request->input('description') ?? 'Payment verification failed',
                'error_id' => $request->input('errorId'),
                'reason' => $request->input('reason'),
                'step' => $this->step,
                'payment_type' => 'observation'
            ]));
        }
        if ($request->has('fawryRefNumber') || $request->has('merchantRefNumber')) {
            $data = [
                'payment_id' => $request->input('merchantRefNumber'),
                'fawryRefNumber' => $request->input('fawryRefNumber')
            ];
        } else {
            $data = $request->validate([
                'payment_id' => 'required|string',
            ]);
        }

        try {
            $verify = $this->bookingService->verifyFawry($data);

            // ✅ تكوين بيانات المستخدم من ال verify
            $userJson = null;
            $userStep = null;

            if (isset($verify['user_id'])) {
                $user = \App\Models\CognifyParent::find($verify['user_id']);

                if ($user) {
                    $newToken = $user->createToken('api-token')->plainTextToken;
                    $userStep = $user->step;
                    $userData = [
                        'step' => $userStep,
                        'token' => $newToken,
                    ];
                    $userJson = urlencode(json_encode($userData));
                }
            }

            // ✅ الدفع ناجح

            // ✅ الدفع ناجح
            if (isset($verify['status']) && $verify['status'] === true) {
                Log::info('Payment verification successful', [
                    'data' => $data,
                    'response' => $verify
                ]);

                // ✅ تكوين بيانات المستخدم من الـ verify
                $userJson = null;
                $userStep = null;
                $token = null;

                if (isset($verify['user_id'])) {
                    $user = \App\Models\CognifyParent::find($verify['user_id']);
                } else {
                    $user = auth()->user();
                }

                if ($user) {
                    $token = $user->createToken('api-token')->plainTextToken;
                    // Ensure step is always present by falling back to controller's stored step
                    $userStep = $user->step ?? $this->step ?? null;
                    $userData = [
                        'step' => $userStep,
                        'token' => $token,
                    ];
                    $userJson = urlencode(json_encode($userData));
                } else {
                    // No user found; still include step if available from controller state
                    $userStep = $this->step ?? null;
                }

                return redirect()->to('https://cognfiy.vercel.app/payment-success?' . http_build_query([
                    'success' => true,
                    'transaction_number' => $verify['fawryRefNumber'] ?? $data['fawryRefNumber'] ?? null,
                    'user' => $userJson,
                    'message' => $verify['message'] ?? 'Payment successful',
                    'step' => $userStep,
                ]));
            }


            // ❌ الدفع فشل
            Log::warning('Payment verification failed', [
                'data' => $data,
                'response' => $verify
            ]);

            return redirect()->to('https://cognfiy.vercel.app/payment-failed?' . http_build_query([
                'success' => false,
                'message' => $verify['message'] ?? 'Payment verification failed',
                'payment_id' => $data['payment_id'] ?? 'Unknown',
                'step' => $userStep,
                'payment_type' => 'observation'
            ]));
        } catch (\Exception $e) {
            Log::error('Payment verification exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->to('https://cognfiy.vercel.app/payment-failed?' . http_build_query([
                'success' => false,
                'message' => 'An unexpected error occurred during payment verification',
                'description' => 'Please contact support with this error ID: ' . uniqid(),
                'step' => $this->step,
                'payment_type' => 'observation'
            ]));
        }
    }
}
