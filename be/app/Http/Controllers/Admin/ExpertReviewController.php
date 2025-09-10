<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExpertReview;
use App\Models\Expert;
use App\Models\Category;
use Illuminate\Http\Request;

class ExpertReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $productId = $request->query('product_id');
        $reviews = \App\Models\ExpertReview::where('product_id', $productId)
            ->select('expert_reviews.*') // LẤY TRỰC TIẾP TỪ expert_reviews
            ->get();
        return response()->json($reviews);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Lấy tất cả danh mục và sản phẩm thuộc từng danh mục, sắp xếp Product_ID giảm dần
        $categories = \App\Models\Category::with(['products' => function($q) {
            $q->orderByDesc('Product_ID');
        }])->get();

        $experts = Expert::all();
        return view('admin.expert_reviews.create', compact('categories', 'experts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,Product_ID',
            'expert_id'  => 'required|exists:experts,id',
            'content'    => 'required|string',
        ]);

        // Đổi key từ 'expert_id' sang 'Expert_ID' nếu cần
        if ($request->has('expert_id')) {
            $data['Expert_ID'] = $request->input('expert_id');
            unset($data['expert_id']);
        }

        ExpertReview::create($data);
        return redirect()->route('admin.expert-reviews.index')->with('success', 'Thêm nhận xét thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $review = ExpertReview::with(['expert', 'product'])->findOrFail($id);
        return view('admin.expert_reviews.show', compact('review'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $review = ExpertReview::findOrFail($id);
        // Lấy tất cả danh mục và sản phẩm thuộc từng danh mục, sắp xếp Product_ID giảm dần
        $categories = \App\Models\Category::with(['products' => function($q) {
            $q->orderByDesc('Product_ID');
        }])->get();

        $experts = Expert::all();
        return view('admin.expert_reviews.edit', compact('review', 'categories', 'experts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $review = ExpertReview::findOrFail($id);
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'expert_id'  => 'required|exists:experts,id',
            'content'    => 'required|string',
        ]);
        $review->update($data);
        return redirect()->route('admin.expert-reviews.index')->with('success', 'Cập nhật nhận xét thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $review = ExpertReview::findOrFail($id);
        $review->delete();
        return redirect()->route('admin.expert-reviews.index')->with('success', 'Xóa nhận xét thành công!');
    }
}
