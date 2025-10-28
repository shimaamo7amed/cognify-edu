<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Nafezly\Payments\Classes\FawryPayment;
use App\Services\ObservationChildCaseService;
use App\Http\Requests\ObservationChildCaseRequest;
use Illuminate\Routing\Controller;

class ObservationChildCaseController extends Controller
{
    protected $bookingService;

    public function __construct(ObservationChildCaseService $bookingService)
    {
        $this->bookingService = $bookingService;
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
                'role' => $payment['role'] ?? null,
                'step' => $payment['step'] ?? null,
                'token' => $payment['token'] ?? null,
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
                return view('payments.verify_error', [
                    'message' => 'Invalid payment request',
                    'description' => 'Missing payment token'
                ]);
            }

            $cacheKey = 'fawry_payment_' . $token;
            $paymentData = Cache::get($cacheKey);

            if (!$paymentData) {
                Log::error('Fawry payment data not found in cache', ['token' => $token]);
                return view('payments.verify_error', [
                    'message' => 'Payment session expired or invalid',
                    'description' => 'Please try initiating the payment again.'
                ]);
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

            return view('payments.verify_error', [
                'message' => 'An error occurred while processing your payment',
                'description' => 'Please try again later or contact support.'
            ]);
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

            return view('payments.verify_error', [
                'description' => $request->input('description'),
                'errorId' => $request->input('errorId'),
                'reason' => $request->input('reason'),
                'message' => 'Payment verification failed'
            ]);
        }

        if ($request->has('fawryRefNumber') || $request->has('merchantRefNumber')) {
            Log::info('Fawry direct callback detected', $request->all());

            $data = [
                'payment_id' => $request->input('merchantRefNumber'),
                'fawryRefNumber' => $request->input('fawryRefNumber')
            ];
        } else {
            try {
                $data = $request->validate([
                    'payment_id' => 'required|string',
                ]);
            } catch (\Exception $e) {
                Log::error('Payment verification validation error', [
                    'error' => $e->getMessage(),
                    'params' => $request->all()
                ]);

                return view('payments.verify_error', [
                    'message' => 'Invalid payment verification request',
                    'description' => 'Required parameters are missing'
                ]);
            }
        }

        try {
            $verify = $this->bookingService->verifyFawry($data);

            if (!$verify['status']) {
                Log::warning('Payment verification failed', [
                    'data' => $data,
                    'response' => $verify
                ]);

                return view('payments.verify_error', [
                    'message' => $verify['message'],
                    'payment_id' => $data['payment_id'] ?? 'Unknown'
                ]);
            }

            Log::info('Payment verification successful', [
                'data' => $data,
                'response' => $verify
            ]);

            return view('payments.verify_success', [
                'payment_id' => $data['payment_id'],
                'case_id' => $verify['case_id'],
                'message' => $verify['message']
            ]);

        } catch (\Exception $e) {
            Log::error('Payment verification exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return view('payments.verify_error', [
                'message' => 'An error occurred during payment verification',
                'description' => 'Please contact support with this error ID: ' . uniqid()
            ]);
        }
    }
}
