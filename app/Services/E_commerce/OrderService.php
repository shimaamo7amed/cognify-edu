<?php

namespace App\Services\E_commerce;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Nafezly\Payments\Classes\FawryPayment;

class OrderService
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

    public function createOrder(array $data, Request $request)
    {
        $user = auth('sanctum')->user();
        $userId = $user?->id;
        $sessionId = $userId ? null : $request->header('session-id');

        if (!$userId && !$sessionId) {
            return [
                'status' => false,
                'message' => __('messages.unauthenticatedOrder')
            ];
        }

        $cart = Cart::with('items')->where(function ($q) use ($user, $sessionId) {
            if ($user) {
                $q->where('user_id', $user->id);
            } else {
                $q->where('session_id', $sessionId);
            }
        })->where('status', 'pending')->first();

        if (!$cart) {
            return [
                'status' => false,
                'message' => __('messages.noOrderCartFound')
            ];
        }

        $totalAmount = $cart->items->sum(fn($item) => $item->unit_price * $item->quantity);

        try {
            $fawry = new FawryPayment();

            if ($user) {
                $paymentMethod = $fawry->pay(
                    amount: $totalAmount,
                    user_id: $user->id,
                    user_first_name: $user->name,
                    user_last_name: $user->name,
                    user_email: $user->email,
                    user_phone: $user->phone,
                    source: 'ORDER_' . $cart->id . '_' . time(),
                );
            } else {
                $paymentMethod = $fawry->pay(
                    amount: $totalAmount,
                    user_id: $sessionId,
                    user_first_name: $data['guest_name'] ?? 'Guest',
                    user_last_name: $data['guest_name'] ?? 'Guest',
                    user_email: $data['guest_email'] ?? 'guest@example.com',
                    user_phone: $data['guest_phone'] ?? '0000000000',
                    source: 'ORDER_GUEST_' . $sessionId . '_' . time(),
                );
            }

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
        } catch (\Exception $e) {
            Log::error('Fawry payment failed', ['error' => $e->getMessage()]);
            return [
                'status' => false,
                'message' => 'Payment initialization failed: ' . $e->getMessage()
            ];
        }

        // Build signature for Fawry popup
        $returnUrl = route('verify-payment', ['payment' => 'fawry']) . '?type=order';

        $signature = hash(
            'sha256',
            $this->fawry_merchant .
                $paymentId .
                ($user ? $user->id : $sessionId) .
                $returnUrl .
                "1" .
                "1" .
                number_format($totalAmount, 2, '.', '') .
                $this->fawry_secret
        );

        // Store full payment payload for the checkout page
        Cache::put('fawry_order_' . $paymentId, [
            'cart_id'       => $cart->id,
            'user_id'       => $user?->id ?? $sessionId,
            'session_id'    => $sessionId,
            'user_name'     => $user ? $user->name : ($data['guest_name'] ?? 'Guest'),
            'user_email'    => $user ? $user->email : ($data['guest_email'] ?? 'guest@example.com'),
            'user_phone'    => $user ? $user->phone : ($data['guest_phone'] ?? '0000000000'),
            'guest_address' => $data['guest_address'] ?? ($user?->address ?? null),
            'amount'        => $totalAmount,
            'total_amount'  => $totalAmount,
            'description'   => 'Order #' . $cart->id,
            'signature'     => $signature,
            'payment_id'    => $paymentId,
        ], 3600);

        $paymentData = [
            'payment_id' => $paymentId,
            'user_name' => $user ? $user->name : ($data['guest_name'] ?? 'Guest'),
            'user_email' => $user ? $user->email : ($data['guest_email'] ?? 'guest@example.com'),
            'user_phone' => $user ? $user->phone : ($data['guest_phone'] ?? '0000000000'),
            'user_id' => $user ? $user->id : $sessionId,
            'amount' => $totalAmount,
            'description' => 'Order #' . $cart->id,
            'signature' => $signature,
        ];

        // (Optional legacy key kept for backward compatibility)
        $cacheKey = 'fawry_Orderpayment_' . $paymentId;
        Cache::put($cacheKey, $paymentData, 3600);
        //    dd($paymentData, $returnUrl);
        return [
            'status' => true,
            'payment_id' => $paymentId,
            'redirect_url' => route('fawry.payment.page') . '?token=' . $paymentId . '&type=order',
            'message' => __('messages.proceed_to_payment'),
            'amount' => $totalAmount,
        ];
    }

    public function verifyFawryPayment($data): array
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

            Log::info('Fawry order verification request details', [
                'request_data' => is_array($data) ? $data : $data->all(),
            ]);

            try {
                $verify = $fawry->verify($request);
                Log::info('Fawry order verification response', ['response' => $verify]);
            } catch (\Exception $e) {
                Log::error('Exception in Fawry verify method', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);

                try {
                    $reference_id = null;

                    if (isset($data['merchantRefNumber'])) {
                        $reference_id = $data['merchantRefNumber'];
                    } elseif (isset($data['referenceNumber'])) {
                        $reference_id = $data['referenceNumber'];
                    } elseif ($request->has('merchantRefNumber')) {
                        $reference_id = $request->input('merchantRefNumber');
                    } elseif ($request->has('referenceNumber')) {
                        $reference_id = $request->input('referenceNumber');
                    } elseif ($request->has('chargeResponse')) {
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
                        ]);

                        if ($response->successful()) {
                            $responseData = $response->json();

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
                                ]);
                            } else {
                                $verify = [
                                    'success' => false,
                                    'payment_id' => $reference_id,
                                    'message' => 'Invalid response from Fawry API',
                                ];
                            }
                        } else {
                            $verify = [
                                'success' => false,
                                'payment_id' => $reference_id,
                                'message' => 'Failed to connect to Fawry API: ' . $response->status(),
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
                    ]);
                    throw $e;
                }
            }

            if (!isset($verify['success']) || $verify['success'] !== true) {
                return [
                    'status' => false,
                    'message' => isset($verify['message']) ? $verify['message'] : 'Payment verification failed',
                    'details' => $verify
                ];
            }

            $paymentId = $verify['payment_id'] ??
                (is_array($data) ? ($data['payment_id'] ?? null) : $data->input('payment_id'));

            if (!$paymentId) {
                return ['status' => false, 'message' => 'Payment ID not found in verification response'];
            }

            $cachedData = Cache::get('fawry_order_' . $paymentId);

            if (!$cachedData) {
                return ['status' => false, 'message' => 'Order data not found for payment: ' . $paymentId];
            }

            $cart = Cart::with('items')->find($cachedData['cart_id']);
            if (!$cart) {
                return ['status' => false, 'message' => 'Cart not found'];
            }

            // ✅ Determine the real payment method from Fawry API response
            $gatewayPaymentMethod = null;

            if (isset($verify['process_data'])) {
                $pd = $verify['process_data'];

                $gatewayPaymentMethod =
                    ($pd['paymentMethod'] ?? null) // e.g. "CARD"
                    ?? ($pd['paymentMethodName'] ?? null)
                    ?? ($pd['orderTransactions'][0]['paymentMethod'] ?? null)
                    ?? ($pd['orderTransactions'][0]['paymentMethodName'] ?? null);
            }

            if (!$gatewayPaymentMethod && isset($data['paymentMethod'])) {
                $gatewayPaymentMethod = $data['paymentMethod'];
            }

            Log::info('Detected payment method from Fawry', [
                'gatewayPaymentMethod' => $gatewayPaymentMethod
            ]);

            // Normalize IDs: store numeric user IDs in user_id, others in session_id
            $orderUserId = isset($cachedData['user_id']) && is_numeric($cachedData['user_id'])
                ? (int) $cachedData['user_id']
                : null;
            $orderSessionId = $cachedData['session_id']
                ?? (!is_null($orderUserId) ? null : (isset($cachedData['user_id']) ? (string) $cachedData['user_id'] : null));

            $order = Order::create([
                'user_id'        => $orderUserId,
                'session_id'     => $orderSessionId,
                'cart_id'        => $cart->id,
                'order_number'   => 'ORD-' . strtoupper(uniqid()),
                'status'         => 'completed',
                'subtotal'       => $cachedData['total_amount'],
                'total_amount'   => $cachedData['total_amount'],
                'payment_status' => 'paid',
                // Store the real gateway payment method when available (e.g. CARD, PayUsingCC)
                'payment_method' => $gatewayPaymentMethod ?: 'fawry',
                'payment_id'     => $paymentId,
                'guest_name'     => $cachedData['user_name'] ?? ($cachedData['guest_name'] ?? null),
                'guest_email'    => $cachedData['user_email'] ?? ($cachedData['guest_email'] ?? null),
                'guest_phone'    => $cachedData['user_phone'] ?? ($cachedData['guest_phone'] ?? null),
                'guest_address'  => $cachedData['guest_address'],
            ]);

            foreach ($cart->items as $item) {
                OrderProduct::create([
                    'order_id'   => $order->id,
                    'product_id' => $item->product_id,
                    'quantity'   => $item->quantity,
                    'price'      => $item->unit_price,
                    'total'      => $item->unit_price * $item->quantity,
                ]);
            }

            $cart->update(['status' => 'ordered']);
            Cache::forget('fawry_order_' . $paymentId);

            // Compute real transaction number from verify/process_data or request payload
            $transactionNumber = null;
            if (isset($verify['fawryRefNumber'])) {
                $transactionNumber = $verify['fawryRefNumber'];
            } elseif (isset($verify['referenceNumber'])) {
                $transactionNumber = $verify['referenceNumber'];
            } elseif (isset($verify['process_data'])) {
                $pd = $verify['process_data'];
                $transactionNumber =
                    ($pd['fawryRefNumber'] ?? null)
                    ?? ($pd['referenceNumber'] ?? null)
                    ?? ($pd['paymentRefrenceNumber'] ?? null)
                    ?? ($pd['threeDSInfo']['trxNumber'] ?? null)
                    ?? ($pd['orderTransactions'][0]['referenceNumber'] ?? null)
                    ?? ($pd['orderTransactions'][0]['fawryRefNumber'] ?? null)
                    ?? ($pd['orderTransactions'][0]['paymentRefrenceNumber'] ?? null);
            }
            if (!$transactionNumber) {
                // fallback to incoming data (e.g., ChargeResponse redirect)
                if (is_array($data)) {
                    $transactionNumber = $data['referenceNumber'] ?? $data['authNumber'] ?? null;
                } else {
                    $transactionNumber = $data->input('referenceNumber') ?? $data->input('authNumber');
                }
            }

            return [
                'status' => true,
                'message' => 'Payment verified successfully. Order created.',
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'fawryRefNumber' => $transactionNumber,
                'merchantRefNumber' => $paymentId,
                'process_data' => $verify['process_data'] ?? null,
            ];
        } catch (\Exception $e) {
            Log::error('Exception in verifyFawryPayment', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'status' => false,
                'message' => 'An error occurred during payment verification: ' . $e->getMessage(),
            ];
        }
    }
}