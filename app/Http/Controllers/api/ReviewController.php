<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function reviewByProduct($slug)
    {
        $product = Product::where('slug', $slug)->first();

        // Reviews with user
        $reviews = Review::where('product_id', $product->id)
            ->orderBy('created_at', 'desc')
            ->with('user')
            ->paginate(20);

        // Global review stats
        $totalReviews = Review::where('product_id', $product->id)->count();
        $averageRating = Review::where('product_id', $product->id)->avg('rating');

        // Rating breakdown (1★ - 5★)
        $ratingCounts = Review::where('product_id', $product->id)
            ->selectRaw('rating, COUNT(*) as count')
            ->groupBy('rating')
            ->pluck('count', 'rating');

        // ensure 1–5 star all present (jodi kono ta na thake tahole 0 dekhabe)
        $breakdown = [];
        for ($i = 1; $i <= 5; $i++) {
            $breakdown[$i] = $ratingCounts->get($i, 0);
        }

        return response()->json([
            'status' => true,
            'reviews' => $reviews,
            'total_reviews' => $totalReviews,
            'average_rating' => round($averageRating, 1),
            'rating_breakdown' => $breakdown,
        ]);
    }



    public function store(Request $request)
    {
        $validated = $request->validate([
            'review'     => 'required|string|min:3',
            'product_id' => 'required|string|exists:products,slug',
            'rating'     => 'required|integer|min:1|max:5',
        ]);

        try {
            $user = auth()->user();

            $product = Product::where('slug', $validated['product_id'])->firstOrFail();

            $review = new Review();
            $review->review     = $validated['review'];
            $review->rating     = $validated['rating'];
            $review->product_id = $product->id;
            $review->user_id    = $user->id;
            $review->save();

            return response()->json([
                'success' => true,
                'message' => 'Thank you! Your review has been submitted successfully.',
            ], 201);

        } catch (\Throwable $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $exception->getMessage(),
            ], 500);
        }
    }


}
