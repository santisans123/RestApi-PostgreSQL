<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Item;
use Illuminate\Http\Request;

class ReviewController extends Controller
{

    public function index($itemId)
    {
        $item = Item::findOrFail($itemId);
        return $item->reviews()->with('user:id,name')->get();
    }

    public function getAllReviews(Request $request)
    {
        $limit = $request->input('limit', 10); // default: 10 data per halaman

        $reviews = Review::with(['user:id,name,email', 'item:id,name'])
            ->orderBy('created_at', 'desc')
            ->paginate($limit);

        return response()->json([
            'message' => 'List of all reviews',
            'data' => $reviews
        ]);
    }


    public function store(Request $request, $itemId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
            'is_verified' => 'boolean'
        ]);

        $review = Review::create([
            'user_id' => $request->user()->id,
            'item_id' => $itemId,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_verified' => $request->is_verified ?? false
        ]);

        return response()->json(['message' => 'Review added', 'review' => $review], 201);
    }

    public function update(Request $request, $id)
    {
        $review = Review::where('id', $id)->where('user_id', $request->user()->id)->firstOrFail();

        $request->validate([
            'rating' => 'sometimes|integer|min:1|max:5',
            'comment' => 'nullable|string',
            'is_verified' => 'boolean'
        ]);

        $review->update($request->only(['rating', 'comment', 'is_verified']));

        return response()->json(['message' => 'Review updated', 'review' => $review]);
    }

    public function destroy(Request $request, $id)
    {
        $review = Review::where('id', $id)->where('user_id', $request->user()->id)->firstOrFail();
        $review->delete();

        return response()->json(['message' => 'Review deleted']);
    }
}

