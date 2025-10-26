<?php
namespace App\Services\E_commerce;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class ReviewService
{
    public function reviewService(array $data)
    {
        $user = Auth::user();

        $existingReview = Review::where('user_id', $user->id)
            ->where('is_reviewed', true)
            ->first();

        if ($existingReview) {
            return [
                'error' => true,
                'message' => __('messages.review_already_submitted'),
            ];
        }

        $review = Review::create([
            'user_id'     => $user->id,
            'is_reviewed' => true,
            'review'      => $data['review'],
            'rating'      => $data['rating'],
        ]);

        if ($review) {
            return [
                'error'  => false,
                'id'     => $review->id,
                'review' => $review->review,
                'rating' => $review->rating,
            ];
        }

        return null;
    }
   public function updateReviewService(array $data)
   {
    $user = Auth::user();
    $review = Review::where('user_id', $user->id)
        ->where('is_reviewed', true)
        ->first();

    if (!$review) {
        return false;
    }

    $review->update([
        'review' => $data['review'] ?? $review->review,
        'rating' => $data['rating'] ?? $review->rating,
    ]);

    return $review;
}



}
