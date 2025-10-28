<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Services\ObservationChildCaseService;
use App\Services\E_commerce\OrderService;
use Illuminate\Support\Facades\Cache;

class PaymentController extends Controller
{
    protected $observationService;
    protected $orderService;

    public function __construct(
        ObservationChildCaseService $observationService,
        OrderService $orderService
    ) {
        $this->observationService = $observationService;
        $this->orderService = $orderService;
    }

    public function showFawryPaymentPage(Request $request)
    {
        $token = $request->query('token');
        $type = $request->query('type', 'observation');

        if ($type === 'observation') {
            $controller = new ObservationChildCaseController($this->observationService);
            return $controller->showFawryPaymentPage($request);
        } elseif ($type === 'order') {
            $controller = new \App\Http\Controllers\E_Commerce\OrderController($this->orderService);
            return $controller->showFawryPaymentPage($request);
        }

        return view('payments.verify_error', ['message' => 'Invalid payment type']);
    }

    public function verifyPayment(Request $request, $payment = null)
    {
        $type = $request->query('type', 'observation');

        // Handle duplicate/overridden type params from Fawry (e.g. type=order&type=ChargeResponse)
        if (!in_array($type, ['order', 'observation'], true)) {
            $inferred = null;
            $ref = $request->input('merchantRefNumber');
            if ($ref && Cache::has('fawry_order_' . $ref)) {
                $inferred = 'order';
            } elseif ($ref && Cache::has('fawry_payment_' . $ref)) {
                $inferred = 'observation';
            } elseif ($request->has('orderStatus') || $request->has('orderAmount')) {
                // Heuristic: presence of order fields -> treat as order
                $inferred = 'order';
            }

            if ($inferred) {
                $type = $inferred;
            }
        }

        if ($type === 'observation') {
            $controller = new ObservationChildCaseController($this->observationService);
            return $controller->payment_verify($request);
        } elseif ($type === 'order') {
            $controller = new \App\Http\Controllers\E_Commerce\OrderController($this->orderService);
            return $controller->verifyFawryPayment($request);
        }

        return view('payments.verify_error', ['message' => 'Invalid payment type']);
    }
}
