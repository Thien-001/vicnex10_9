<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductReview;
use Illuminate\Http\Request;

class ProductReviewApiController extends Controller
{
    public function index()
    {
        return response()->json(ProductReview::with('product', 'user', 'order')->get(), 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'Product_ID' => 'required|exists:products,Product_ID',
            'User_ID' => 'required|exists:user,ID',
            'Order_ID' => 'required|exists:orders,Order_ID',
            'Rating' => 'required|integer|min:1|max:5',
            'Comment' => 'nullable|string',
            'Image' => 'nullable|string',
            'Status' => 'boolean',
        ]);

        $review = ProductReview::create($validated);
        return response()->json($review, 201);
    }

    public function show($id)
    {
        $review = ProductReview::with('product', 'user', 'order')->find($id);
        if (!$review) {
            return response()->json(['message' => 'Not found'], 404);
        }
        return response()->json($review, 200);
    }

    public function update(Request $request, $id)
    {
        $review = ProductReview::find($id);
        if (!$review) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $validated = $request->validate([
            'Product_ID' => 'required|exists:products,Product_ID',
            'User_ID' => 'required|exists:user,ID',
            'Order_ID' => 'required|exists:orders,Order_ID',
            'Rating' => 'required|integer|min:1|max:5',
            'Comment' => 'nullable|string',
            'Image' => 'nullable|string',
            'Status' => 'boolean',
        ]);

        $validated['Updated_at'] = now();

        $review->update($validated);
        return response()->json($review, 200);
    }

    public function destroy($id)
    {
        $review = ProductReview::find($id);
        if (!$review) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $review->delete();
        return response()->json(['message' => 'Deleted'], 200);
    }
}
