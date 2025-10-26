<?php

namespace App\Services\E_commerce;

use App\Models\Product;

use App\Models\WishList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistService
{
    public function toggleProduct($productId, Request $request)
    {
        $userId = auth('sanctum')->id();
        $sessionId = null;

        if (!$userId) {
            $providedSessionId = $request->header('session-id');

            if ($providedSessionId && Wishlist::where('session_id', $providedSessionId)->exists()) {
                $sessionId = $providedSessionId;
            } else {
                $sessionId = session()->getId();
            }
        }

        $wishlist = Wishlist::firstOrCreate([
            'user_id'    => $userId,
            'session_id' => $sessionId,
        ]);

        $product = Product::findOrFail($productId);
        $productInWishlist = $wishlist->products()
            ->where('product_id', $productId)
            ->first();

        if ($productInWishlist) {
            $wishlist->products()->detach($productId);
            $status = 'removed';
            $message = __('messages.wishlist_removed');
        } else {
            $wishlist->products()->attach($productId);
            $status = 'added';
            $message = __('messages.wishlist_added');
        }

        return [
            'data' => [
                'id'           => $wishlist->id,
                'user_id'      => $userId,
                'session_id'   => $sessionId,
                'product_id'   => $productId,
                'product_name' => $product->name[app()->getLocale()] ?? 'N/A',
                'status'       => $status,
            ],
            'message' => $message
        ];
    }




    public function getWishlist(Request $request)
    {
        $user = auth('sanctum')->user();
        $userId = $user?->id;
        $sessionId = $userId ? null : ($request->header('session-id') ?? session()->getId());

        $wishlist = Wishlist::with('products')
            ->where(function ($query) use ($userId, $sessionId) {
                if ($userId) {
                    $query->where('user_id', $userId);
                } else {
                    $query->where('session_id', $sessionId);
                }
            })
            ->first(); 

        return $wishlist;
    }


    public function remove(int $id, Request $request)
{
    $user = auth('sanctum')->user();
    $userId = $user?->id;
    $sessionId = $userId ? null : ($request->header('session-id') ?? session()->getId());

    $wishlist = Wishlist::where(function ($query) use ($userId, $sessionId) {
        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('session_id', $sessionId);
        }
    })->first();

    if (!$wishlist) {
        return [
            'data' => [],
            'message' => __('messages.wishlist_empty')
        ];
    }
    if ($wishlist->id === $id) {
        $wishlist->products()->detach();
        $wishlist->delete();

        return [
            'data' => ['deleted' => true],
            'message' => __('messages.wishlist_deleted_all'),
        ];
    } else {
        $deleted = $wishlist->products()->detach($id);

        return [
            'data' => [
                'deleted' => (bool) $deleted,
                'product_id' => $id,
            ],
            'message' => __('messages.wishlist_deleted'),
        ];
    }
}

}