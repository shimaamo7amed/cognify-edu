<?php

namespace App\Services;

use App\Models\Order;
use App\Models\ObservationChildCase;
use Illuminate\Http\Request;

class OrderObservationService
{
    public function getOrderDetailsById(Request $request, $id)
    {
        $user = auth('sanctum')->user();
        $sessionId = $request->header('session-id');
        $type = $request->query('type', 'order'); // default = order

        if ($type === 'order') {
            $order = Order::find($id);
            if (!$order) {
                return [
                    'status' => false,
                    'message' => 'Order not found.'
                ];
            }

            // 🔒 تحقق من الملكية
            if (
                ($user && $order->user_id != $user->id) ||
                (!$user && $order->session_id != $sessionId)
            ) {
                return [
                    'status' => false,
                    'message' => 'Unauthorized access to this order.'
                ];
            }

            return [
                'status' => true,
                'type' => 'order',
                'data' => [
                    'amount' => isset($observation->total_amount)
                    ? (string) (int) $observation->total_amount
                    : "0",
                    'payment_method' => $order->payment_method ?? 'CAERD',
                    'created_at' => $order->created_at?->format('Y-m-d H:i:s'),
                ]
            ];
        }

        if ($type === 'observation') {
            $observation = ObservationChildCase::find($id);
            if (!$observation) {
                return [
                    'status' => false,
                    'message' => 'Observation not found.'
                ];
            }

            // 🔒 تحقق من الملكية
            if ($user) {
                $childParent = $observation->child?->parent;
                if ($childParent && $childParent->id != $user->id) {
                    return [
                        'status' => false,
                        'message' => 'Unauthorized access to this observation.'
                    ];
                }
            } else {
                return [
                    'status' => false,
                    'message' => 'Unauthorized - login required to access observation.'
                ];
            }

            return [
                'status' => true,
                'type' => 'observation',
                'data' => [
                    'amount' => isset($observation->total_amount)
                    ? (string) (int) $observation->total_amount
                    : "0",
                    'payment_method' => $observation->payment_method ?? 'CAERD',
                    'created_at' => $observation->created_at?->format('Y-m-d H:i:s'),
                ]
            ];
        }

        return [
            'status' => false,
            'message' => 'Invalid type parameter. Allowed: order, observation'
        ];
    }
}
