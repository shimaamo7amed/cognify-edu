<?php

namespace App\Http\Controllers\E_Commerce;

use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;
use App\Services\E_commerce\ProductService;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    protected $ProductService;

    public function __construct(ProductService $ProductService)
    {
        $this->ProductService = $ProductService;
    }
    public function getAllProducts(Request $request)
    {
        $limit = $request->input('limit', 3);

        $products = $this->ProductService->getAllProducts($request, $limit);

        if ($products->total() === 0) {
            return apiResponse(false, [], __('messages.no_products_found'));
        }
        $data = ProductResource::collection($products->items());
        $pagination = [
            'current_page'   => $products->currentPage(),
            'from'           => $products->firstItem(),
            'to'             => $products->lastItem(),
            'per_page'       => $products->perPage(),
            'last_page'      => $products->lastPage(),
            'total'          => $products->total(),
            'first_page_url' => $products->url(1),
            'last_page_url'  => $products->url($products->lastPage()),
            'next_page_url'  => $products->nextPageUrl(),
            'prev_page_url'  => $products->previousPageUrl(),
            'path'           => $products->path(),
        ];

        return apiResponse(true, [
            'products'   => $data,
            'pagination' => $pagination,
        ], __('messages.products_found'));
    }



    public function GetProductBySlug($slug)
    {
        $product = $this->ProductService->getProductBySlug($slug);
        // dd($product);
        if (!$product) {
            return apiResponse(false, [], __('messages.product_not_found'));
        }
        return apiResponse(true, new ProductResource($product), __('messages.product_found'));
    }
}
