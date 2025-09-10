<?php

namespace App\Http\Controllers;

use App\Models\ProductReview;
use App\Models\Product;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;

class ProductReviewController extends Controller
{
    public function index()
    {
        $reviews = ProductReview::with('product', 'user', 'order')->get();
        return view('product_reviews.index', compact('reviews'));
    }

    public function create()
    {
        $products = Product::all();
        $users = User::all();
        $orders = Order::all();
        return view('product_reviews.create', compact('products', 'users', 'orders'));
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

        ProductReview::create($validated);
        return redirect()->route('product_reviews.index')->with('success', 'Review created successfully.');
    }

    public function show($id)
    {
        $review = ProductReview::with('product', 'user', 'order')->findOrFail($id);
        return view('product_reviews.show', compact('review'));
    }

    public function edit($id)
    {
        $review = ProductReview::findOrFail($id);
        $products = Product::all();
        $users = User::all();
        $orders = Order::all();
        return view('product_reviews.edit', compact('review', 'products', 'users', 'orders'));
    }

    public function update(Request $request, $id)
    {
        $review = ProductReview::findOrFail($id);

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
        return redirect()->route('product_reviews.index')->with('success', 'Review updated successfully.');
    }

    public function destroy($id)
    {
        $review = ProductReview::findOrFail($id);
        $review->delete();
        return redirect()->route('product_reviews.index')->with('success', 'Review deleted successfully.');
    }
}
