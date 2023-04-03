<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductRatingResource;
use App\Http\Resources\ProductReviewResource;
use Illuminate\Http\Request;
use App\Models\ProductRating;
use App\Models\ProductReview;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    use HttpResponses;

    public function giveReview(Request $request)
    {
        $request->validate([
            'rating' => 'required'
        ]);

        if ($request->rating) {
            $productRating = new ProductRating();
            $productRating->count = $request->rating;
            $productRating->product_id = $request->product_id;
            $productRating->user_id = Auth::id();
            $productRating->save();
        }

        if ($request->review) {
            $productReview = new ProductReview();
            $productReview->review = $request->review;
            $productReview->product_id = $request->product_id;
            $productReview->user_id = Auth::id();
            $productReview->save();
        }

        return $this->success([
            'rating' => new ProductRatingResource(ProductRating::find($productRating->id)->with(['product', 'user'])->first()),
            'review' => $request->review ? new ProductReviewResource(ProductReview::find($productReview->id)->with(['product', 'user'])->first()) : null,
        ], 'Successfully give review');
    }
}
