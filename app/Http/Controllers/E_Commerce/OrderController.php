<?php

namespace App\Http\Controllers\E_Commerce;

use App\Http\Controllers\Controller;
use App\Services\E_commerce\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function __construct(protected OrderService $orderService)
    {
    }

    public function createOrder(Request $request)
    {
        try {
            Log::info('Fawry order request', ['data' => $request->all()]);
            $response = $this->orderService->createOrder($request->all(), $request);
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

        return view('orders.fawry_custom', [
            ...$paymentData,
            'token' => $token,
            'redirect_url' => route('fawry.payment.page', ['token' => $token]),
        ]);
    }

    public function verifyFawryPayment(Request $request)
    {
        Log::info('Fawry verification request', ['data' => $request->all()]);
        $response = $this->orderService->verifyFawryPayment($request->all());

        if ($response['status']) {
            return view('orders.verify_success', ['message' => $response['message']]);
        }

        return view('orders.verify_error', ['message' => $response['message']]);
    }
}
