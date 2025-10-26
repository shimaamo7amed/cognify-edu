<?php

namespace App\Services\E_commerce;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\CartItemResource;

class CartService
{
    public function addToCart(array $data, Request $request)
    {
        $user = auth('sanctum')->user();
        $userId = $user?->id;
        $sessionId = null;

        if (!$userId) {
            $sessionId = $request->header('session-id') ?? session()->getId();
        }

        $product = Product::find($data['product_id']);
        if (!$product) {
            throw new \Exception(__('messages.product_not_found'), 404);
        }

        if ($product->quantity < $data['quantity']) {
            throw new \Exception(__('messages.Insufficient_stock'), 400);
        }

        return DB::transaction(function () use ($data, $userId, $sessionId, $product) {
            $cart = Cart::where(function ($query) use ($userId, $sessionId) {
                if ($userId) {
                    $query->where('user_id', $userId);
                } else {
                    $query->where('session_id', $sessionId);
                }
            })->where('status', 'pending')->first();

            if (!$cart) {
                $cart = Cart::create([
                    'user_id'    => $userId,
                    'session_id' => $sessionId,
                    'status'     => 'pending',
                ]);
            }

            $cartItem = $cart->items()->where('product_id', $data['product_id'])->first();

            $unitPrice = (float) $product->price;
            $quantity = (int) $data['quantity'];
            $totalPrice = $unitPrice * $quantity;

            if ($cartItem) {
                $cartItem->quantity += $quantity;
                $cartItem->total_price = $cartItem->quantity * $unitPrice;
                $cartItem->save();
            } else {
                $cartItem = $cart->items()->create([
                    'product_id'  => $data['product_id'],
                    'quantity'    => $quantity,
                    'unit_price'  => $unitPrice,
                    'total_price' => $totalPrice,
                ]);
            }

            return [
                'user_id'    => $userId,
                'session_id' => $sessionId,
                'cart_item'  => [
                    'id'          => $cartItem->id,
                    'cart_id'     => $cartItem->cart_id,
                    'product_id'  => $cartItem->product_id,
                    'quantity'    => $cartItem->quantity,
                    'unit_price'  => round((float) $cartItem->unit_price, 2),
                    'total_price' => round((float) $cartItem->total_price, 2),
                    'created_at'  => $cartItem->created_at->format('d-m-Y'),
                    'updated_at'  => $cartItem->updated_at->format('d-m-Y'),
                ]
            ];
        });
    }

    public function getCart(Request $request): array
    {
        $user = auth('sanctum')->user();
        $userId = $user?->id;
        $sessionId = $userId ? null : ($request->header('session-id') ?? session()->getId());

        $cart = Cart::with('items.product.tags', 'items.product.category', 'user')
            ->where(function ($query) use ($userId, $sessionId) {
                if ($userId) {
                    $query->where('user_id', $userId);
                } else {
                    $query->where('session_id', $sessionId);
                }
            })
            ->where('status', 'pending')
            ->first();

        // لو مفيش كارت أو الكارت فاضي
        if (!$cart || $cart->items->isEmpty()) {
            return [];
        }

        $cartTotalPrice = $cart->items->sum('total_price');

        return [
            'cart_id'          => $cart->id,
            'user_id'          => $cart->user_id,
            'session_id'       => $cart->session_id,
            'cart_total_price' => $cartTotalPrice,
            'checkout' => $cart->user ? [
                'name'    => $cart->user->name,
                'phone'   => $cart->user->phone,
                'address' => $cart->user->address,
                'governorate' => $cart->user->governorate
            ] : null,
            'items' => CartItemResource::collection($cart->items),
        ];
    }

    public function deleteFromCart($user = null, $sessionId = null, $productId = null): bool {
            $cart = Cart::where([ 'user_id' => $user?->id, 'session_id' => $user ? null :
                $sessionId, 'status' => 'pending', ])->first();
                if (!$cart) {
                    return false;
                    }
                    if ($productId)
                    {
                        $deleted = $cart->items()->where('product_id', $productId)->delete(); return $deleted > 0; 
                     } 
                 $cart->items()->delete(); return true;
    }
    public function updateProductQuantity(array $data)
    {
        $user = $data['user'];
        $sessionId = $data['session_id'];

        $cart = Cart::where('status', 'pending')
            ->when($user, fn ($q) => $q->where('user_id', $user->id))
            ->when(!$user, fn ($q) => $q->where('session_id', $sessionId))
            ->first();

        if (!$cart) {
            return [
                'success' => false,
                'message' => __('messages.cartNotFound'),
                'status' => 404,
            ];
        }

        $cartItem = $cart->items()->where('product_id', $data['product_id'])->first();

        if (!$cartItem) {
            return [
                'success' => false,
                'message' => __('messages.productNotFound'),
                'status' => 404,
            ];
        }

        $cartItem->quantity = $data['quantity'];
        $cartItem->total_price = $data['quantity'] * $cartItem->unit_price;
        $cartItem->save();

        $totalQuantity = $cart->items()->sum('quantity');
        $totalPrice = $cart->items()->sum(DB::raw('quantity * unit_price'));

        return [
            'success' => true,
            'message' => __('messages.quantityUpdated'),
            'status' => 200,
            'data' => [
                'product_id' => $cartItem->product_id,
                'updated_quantity' => $cartItem->quantity,
                'subtotal' => $cartItem->total_price,
                'cart_total_quantity' => $totalQuantity,
                'cart_total_price' => $totalPrice,
            ],
        ];
    }



















}
