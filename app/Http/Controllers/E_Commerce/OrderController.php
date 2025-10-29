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
            return redirect()->to('http://localhost:5173/payment-failed?' . http_build_query([
                'success' => false,
                'message' => 'Payment session expired',
                'payment_type' => 'order'
            ]));
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

        try {
            if ($request->has('merchantRefNumber')) {
                $paymentId = $request->input('merchantRefNumber');
                $cacheKey = 'fawry_order_' . $paymentId;
                Log::info('Debug order cache existence', [
                    'cache_key' => $cacheKey,
                    'exists' => Cache::has($cacheKey)
                ]);
            } else {
                $paymentId = $request->input('payment_id');
            }
            $verify = $this->orderService->verifyFawryPayment($request->all());

        $transactionNumber =
            ($verify['fawryRefNumber'] ?? null)
            ?? ($verify['paymentRefrenceNumber'] ?? null) 
            ?? ($verify['referenceNumber'] ?? null)
            ?? ($verify['merchantRefNumber'] ?? null)
            ?? ($verify['process_data']['fawryRefNumber'] ?? null)
            ?? ($verify['process_data']['paymentRefrenceNumber'] ?? null)
            ?? ($verify['process_data']['referenceNumber'] ?? null)
            ?? ($verify['process_data']['orderTransactions'][0]['referenceNumber'] ?? null)
            ?? ($verify['process_data']['orderTransactions'][0]['fawryRefNumber'] ?? null)
            ?? ($verify['process_data']['orderTransactions'][0]['paymentRefrenceNumber'] ?? null)
            ?? ($verify['details']['referenceNumber'] ?? null)
            ?? ($verify['details']['process_data']['fawryRefNumber'] ?? null)
            ?? ($verify['details']['process_data']['referenceNumber'] ?? null)
            ?? ($verify['details']['process_data']['orderTransactions'][0]['referenceNumber'] ?? null)
            ?? ($verify['details']['process_data']['orderTransactions'][0]['fawryRefNumber'] ?? null)
            ?? ($verify['details']['process_data']['orderTransactions'][0]['paymentRefrenceNumber'] ?? null);
            if (isset($verify['status']) && $verify['status'] === true) {

                Log::info('Fawry order verification successful', [
                    'transaction_number' => $transactionNumber,
                    'verify_response' => $verify
                ]);

                return redirect()->to('http://localhost:5173/payment-success?' . http_build_query([
                    'success' => true,
                    'transaction_number' => $transactionNumber,
                    'order_id' => $verify['order_id'] ?? null,
                    'payment_type' => 'order',
                ]));
            }
            Log::warning('Fawry order verification failed', [
                'verify_response' => $verify
            ]);

            return redirect()->to('http://localhost:5173/payment-failed?' . http_build_query([
                'success' => false,
                'message' => $verify['message'] ?? 'Payment verification failed',
                'payment_type' => 'order',
            ]));
        } catch (\Exception $e) {
            Log::error('Fawry order verification exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->to('http://localhost:5173/payment-failed?' . http_build_query([
                'success' => false,
                'message' => 'An unexpected error occurred during order payment verification',
                'payment_type' => 'order',
            ]));
        }
    }
}
