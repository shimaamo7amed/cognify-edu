<?php

namespace App\Services\E_commerce;

use App\Models\Order;
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
    public function getPurchasedProducts()
    {
        $user = auth('sanctum')->user();
        $sessionId = request()->header('session-id');

        if (!$user && !$sessionId) {
            return [
                'status' => false,
                'message' => __('messages.unauthenticatedOrder'),
                'data' => [],
            ];
        }

        $orders = Order::query();

        if ($user) {
            $orders->where('user_id', $user->id);
        } elseif ($sessionId) {
            $orders->where('session_id', $sessionId);
        }

        $orderIds = $orders->pluck('id');

        $productIds = OrderProduct::whereIn('order_id', $orderIds)
            ->pluck('product_id')
            ->unique()
            ->values();

        $reviews = ProductReview::query()
            ->when($user, fn($q) => $q->where('user_id', $user->id))
            ->when($sessionId, fn($q) => $q->where('session_id', $sessionId))
            ->get(['product_id', 'rate']);

        $productsWithStatus = $productIds->map(function ($productId) use ($reviews) {
            $review = $reviews->firstWhere('product_id', $productId);
            $isRated = $review !== null;

            $data = [
                'product_id' => $productId,
                'is_rated' => $isRated,
            ];

            if ($isRated) {
                $data['user_rate'] = (int) $review->rate;
            }

            return $data;
        });

        return [
            'status' => true,
            'message' => __('messages.data_found'),
            'data' => $productsWithStatus,
        ];
    }



}