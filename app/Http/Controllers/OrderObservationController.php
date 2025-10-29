<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OrderObservationService;

class OrderObservationController extends Controller
{
    protected $orderObservationService;

    public function __construct(OrderObservationService $orderObservationService)
    {
        $this->orderObservationService = $orderObservationService;
    }

    public function getDetails(Request $request, $id)
    {
        $data = $this->orderObservationService->getOrderDetailsById($request, $id);

        if (!$data['status']) {
            return response()->json([
                'status' => false,
                'message' => $data['message']
            ], 404);
        }

        return response()->json([
            'status' => true,
            'type' => $data['type'],
            'data' => $data['data']
        ]);
    }
}
