<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductRating;

class ProductRatingController extends Controller
{
    // Gửi đánh giá sản phẩm (tối đa 5 ảnh, có text)
    public function store(Request $request, $productId)
    {
        $request->validate([
            'User_ID' => 'required|exists:user,ID',
            'Rating' => 'required|integer|min:1|max:5',
            'images.*' => 'image|max:4096', // validate từng ảnh
            'text' => 'nullable|string', // SỬA 'comment' thành 'text'
        ]);

        // Xử lý nhiều ảnh
        $imageNames = [];
        if ($request->hasFile('images')) {
            $files = $request->file('images');
            $files = array_slice($files, 0, 5); // chỉ lấy tối đa 5 ảnh
            foreach ($files as $file) {
                $path = $file->store('uploads/ratings', 'public');
                $imageNames[] = $path;
            }
        }

        // Tìm hoặc tạo mới đánh giá
        $rating = ProductRating::updateOrCreate(
            ['Product_ID' => $productId, 'User_ID' => $request->User_ID],
            [
                'Rating' => $request->Rating,
                'image' => implode(',', $imageNames),
                'text' => $request->text, // SỬA 'comment' thành 'text'
            ]
        );

        return response()->json(['message' => 'Đánh giá thành công!', 'data' => $rating], 201);
    }

    // Lấy điểm trung bình và số lượt đánh giá
    public function stats($productId)
    {
        $avg = ProductRating::where('Product_ID', $productId)->avg('Rating');
        $count = ProductRating::where('Product_ID', $productId)->count();
        return response()->json(['avg' => $avg ?? 0, 'count' => $count]);
    }

    // API trả về danh sách đánh giá cho FE (trả về mảng ảnh)
    public function list($productId)
    {
        $reviews = \App\Models\ProductRating::where('Product_ID', $productId)
            ->orderByDesc('id')
            ->with('user')
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'User_ID' => $item->User_ID,
                    'user_name' => $item->user ? $item->user->Name : 'Ẩn danh',
                    'Rating' => $item->Rating,
                    'images' => $item->image ? array_filter(explode(',', $item->image)) : [],
                    'text' => $item->text, // SỬA 'comment' thành 'text'
                    'created_at' => $item->created_at,
                ];
            });

        $avg = \App\Models\ProductRating::where('Product_ID', $productId)->avg('Rating');
        $count = \App\Models\ProductRating::where('Product_ID', $productId)->count();

        return response()->json([
            'avg' => $avg ?? 0,
            'count' => $count,
            'reviews' => $reviews
        ]);
    }

    // Lấy top đánh giá cao nhất toàn shop
    public function topReviews($limit = 6)
    {
        $reviews = \DB::table('product_ratings')
            ->join('users', 'product_ratings.User_ID', '=', 'users.ID')
            ->leftJoin('products', 'product_ratings.Product_ID', '=', 'products.Product_ID')
            ->select(
                'product_ratings.id',
                'users.Name as user_name',
                'product_ratings.Rating',
                'product_ratings.image',
                'product_ratings.text as text', // Đúng cột text
                'product_ratings.created_at',
                'products.Name as product_name'
            )
            ->orderByDesc('product_ratings.Rating')
            ->orderByDesc('product_ratings.created_at')
            ->limit($limit)
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'user_name' => $item->user_name,
                    'Rating' => $item->Rating,
                    'image' => $item->image,
                    'text' => $item->text,
                    'created_at' => $item->created_at,
                    'product_name' => $item->product_name,
                ];
            });

        return response()->json($reviews);
    }
}
