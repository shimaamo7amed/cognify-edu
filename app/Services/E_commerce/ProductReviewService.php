<?php

namespace App\Services\E_commerce;

use App\Models\OrderProduct;
use Illuminate\Http\Request;
use App\Models\ProductReview;

class ProductReviewService
{
    public function addReview(array $data, Request $request)
    {
        $user = auth('sanctum')->user();
        $userId = $user?->id;
        $sessionId = $userId ? null : $request->header('session-id');

        if (!$userId && !$sessionId) {
            return [
                'status' => false,
                'message' => __('messages.unauthenticated'),
            ];
        }
        $hasPurchased = OrderProduct::whereHas('order', function ($q) use ($userId, $sessionId) {
            if ($userId) {
                $q->where('user_id', $userId);
            } elseif ($sessionId) {
                $q->where('session_id', $sessionId);
            }
        })->where('product_id', $data['product_id'])->exists();

        if (!$hasPurchased) {
            return [
                'status' => false,
                'message' => __('messages.only_purchased_can_rate'),
            ];
        }

        // تحديث أو إنشاء تقييم
        $conditions = ['product_id' => $data['product_id']];
        if ($userId) {
            $conditions['user_id'] = $userId;
        } else {
            $conditions['session_id'] = $sessionId;
        }

        $review = ProductReview::updateOrCreate(
            $conditions,
            ['rate' => $data['rate']]
        );

        return [
            'status' => true,
            'message' => __('messages.review_added'),
            'data' => $review,
        ];
    }
}
