<?php

namespace App\Http\Controllers\E_Commerce;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductReviewRequest;
use App\Services\E_commerce\ProductReviewService;

class ProductReviewController extends Controller
{
    protected ProductReviewService $productReviewService;

    public function __construct(ProductReviewService $productReviewService)
    {
        $this->productReviewService = $productReviewService;
    }

    public function addReview(ProductReviewRequest $request)
    {
        $review = $this->productReviewService->addReview($request->validated(), $request);

        return apiResponse(
            $review['status'],
            $review['data'] ?? [],
            $review['message']
        );
    }
    public function productReviews()
    {
        $result = $this->productReviewService->getPurchasedProducts();

          return apiResponse(
            $result['status'],
            $result['data'] ?? [],
            $result['message']
        );
    
    }


}
