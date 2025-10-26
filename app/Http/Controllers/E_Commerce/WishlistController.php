<?php

namespace App\Http\Controllers\E_Commerce;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\WishlistResource;
use App\Http\Requests\StoreProductRequest;
use App\Services\E_commerce\WishlistService;

class WishlistController extends Controller
{
    protected $wishlistService;

    public function __construct(WishlistService $wishlistService)
    {
        $this->wishlistService = $wishlistService;
    }

    public function toggle(StoreProductRequest $request)
    {
        $result = $this->wishlistService->toggleProduct($request->product_id, $request);

        return apiResponse(true, $result['data'], $result['message']);
    }




    public function index(Request $request)
    {
        $wishlist = $this->wishlistService->getWishlist($request);

        if (!$wishlist || $wishlist->products->isEmpty()) {
            return apiResponse(true, [], __('messages.wishlist_empty'));
        }

        return apiResponse(true, new WishlistResource($wishlist), null);
    }

    public function remove(int $id, Request $request)
{
    $result = $this->wishlistService->remove($id, $request);
    return apiResponse(true, $result['data'], $result['message']);
}




}
