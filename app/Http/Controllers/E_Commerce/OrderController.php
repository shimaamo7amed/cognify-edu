<?php

namespace App\Http\Controllers\E_Commerce;

use Illuminate\Routing\Controller;
use App\Services\E_commerce\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function __construct(protected OrderService $orderService) {}

    public function createOrder(Request $request)
    {
        try {
            Log::info('Fawry order request', ['data' => $request->all()]);
            $response = $this->orderService->createOrder($request->all(), $request);
            // dd($response);
            Log::info('Fawry order response', ['response' => $response]);

            return response()->json($response, $response['status'] ? 200 : 422);
        } catch (\Exception $e) {
            Log::error('Error in createOrder', ['error' => $e->getMessage()]);
            return response()->json(['status' => false, 'message' => 'Something went wrong'], 500);
        }
    }
    public function showFawryPaymentPage(Request $request)
    {
        $token = $request->query('token');
        $cacheKey = 'fawry_order_' . $token;
        $paymentData = Cache::get($cacheKey);

        if (!$paymentData) {
            return view('orders.verify_error', ['message' => 'Payment session expired']);
        }

        // Reuse the same generic payment view used for observations to initiate checkout
        return view('payments.fawry_custom', [
            'message'      => __('messages.proceed_to_payment') ?? 'Redirecting to payment... ',
            'payment_id'   => $paymentData['payment_id'] ?? $token,
            'user_name'    => $paymentData['user_name'] ?? null,
            'user_email'    => $paymentData['user_email'] ?? null,
            'user_phone'    => $paymentData['user_phone'] ?? null,
            'user_id'      => $paymentData['user_id'] ?? null,
            'amount'       => $paymentData['amount'] ?? ($paymentData['total_amount'] ?? 0),
            'description'  => $paymentData['description'] ?? ('Order #' . ($paymentData['cart_id'] ?? '')),
            'signature'    => $paymentData['signature'] ?? null,
            'redirect_url' => route('verify-payment', ['payment' => 'fawry']) . '?type=order',
        ]);
    }



    public function verifyFawryPayment(Request $request)
    {
        Log::info('Fawry verification request', ['data' => $request->all()]);

        // Debug: check if order cache exists for incoming merchantRefNumber
        if ($request->has('merchantRefNumber')) {
            $paymentId = $request->input('merchantRefNumber');
            $cacheKey = 'fawry_order_' . $paymentId;
            Log::info('Debug order cache existence', [
                'cache_key' => $cacheKey,
                'exists' => Cache::has($cacheKey)
            ]);
        }
        $response = $this->orderService->verifyFawryPayment($request->all());

        if ($response['status']) {
            return view('orders.verify_success', ['message' => $response['message']]);
        }

        return view('orders.verify_error', ['message' => $response['message']]);
    }
}
