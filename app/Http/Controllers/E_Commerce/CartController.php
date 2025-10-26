<?php

namespace App\Http\Controllers\E_Commerce;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCartRequest;
use App\Services\E_commerce\CartService;
use App\Http\Requests\UpdateCartItemRequest;
use App\Http\Requests\UpdateCartQuantityRequest;

class CartController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }
    public function addToCart(StoreCartRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $cartItem = $this->cartService->addToCart($data, $request);


            return  apiResponse(true, $cartItem, __('messages.cartAdd'));
        } catch (\Exception $e) {
            $statusCode = $e->getCode() >= 100 && $e->getCode() < 600 ? $e->getCode() : 500;
            return apiResponse(false, [], $e->getMessage(), $statusCode);
        }
    }


    public function show(Request $request)
    {
        $cartData = $this->cartService->getCart($request);

        if (empty($cartData)) {
            return apiResponse(false, [], __('messages.noCartFound'));
        }

        return apiResponse(
            true,
            $cartData,
            __('messages.cartRetrive')
        );
    }


    
    public function deleteCart(Request $request) {
         $request->validate([ 'product_id' => ['sometimes', 'exists:products,id'], ]);
         $user = auth('sanctum')->user();
         $sessionId = $user ? null : ($request->header('session-id') ?? session()->getId());
         $result = $this->cartService->deleteFromCart($user, $sessionId, $request->product_id ?? null);
         if (!$result) { 
             return apiResponse(false, [], __('messages.productNotFoundInCart'));
            }
             return apiResponse(true, [], __('messages.deletedSuccessfully'));
    }



    public function updateQuantity(UpdateCartQuantityRequest $request)
    {
        $user = auth('sanctum')->user();
        $sessionId = $user ? null : $request->header('session-id');

        $result = $this->cartService->updateProductQuantity([
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'user' => $user,
            'session_id' => $sessionId,
        ]);

        return apiResponse(
            $result['success'],
            $result['data'] ?? [],
            $result['message'],
            $result['status']
        );
    }









}
