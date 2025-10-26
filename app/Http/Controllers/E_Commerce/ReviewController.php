<?php

namespace App\Http\Controllers\E_Commerce;

use App\Models\Review;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReviewRequest;
use App\Http\Requests\ReviewUpdateRequest;
use App\Services\E_commerce\ReviewService;

class ReviewController extends Controller
{
    private $reviewService;

    public function __construct(ReviewService $reviewService)
    {
        $this->reviewService = $reviewService;
    }

    public function review(ReviewRequest $request)
    {
        $review = $this->reviewService->reviewService($request->validated());

        if ($review && isset($review['error']) && $review['error'] === true) {
            return apiResponse(false, null, $review['message']);
        }

        if ($review && isset($review['error']) && $review['error'] === false) {
            return apiResponse(true, ['review' => $review], __('messages.review_submitted'));
        }

        return apiResponse(false, null, __('messages.something_went_wrong'));
    }

    public function getReview()
    {
        $reviews = Review::select('id', 'user_id', 'review', 'rating')
            ->with('user:id,name')
            ->where('is_reviewed', true)
            ->get();

        if ($reviews) {
            return apiResponse(true, ['reviews' => $reviews], __('messages.reviews_fetched'));
        } else {
            return apiResponse(false, null, __('messages.no_reviews_found'));
        }

    }
    public function updateReview(ReviewUpdateRequest $request)
   {
    $updated = $this->reviewService->updateReviewService($request->validated());

    if ($updated) {
        return apiResponse(true, ['review' => $updated], __('messages.review_updated'));
    } else {
        return apiResponse(false, null, __('messages.review_not_found'));
    }
}


}
