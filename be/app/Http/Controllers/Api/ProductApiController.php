<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class ProductApiController extends Controller
{
    /**
     * Lấy danh sách sản phẩm có phân trang (12 sản phẩm mỗi trang)
     */
    public function index()
    {
        try {
            $products = Product::with(['category'])->orderBy('created_at', 'desc')->paginate(12);
            
            // Load variants cho từng product
            foreach ($products as $product) {
                $variants = \DB::table('product_variants')
                    ->where('Product_ID', $product->Product_ID)
                    ->get();
                
                // DEBUG: Log để check
                if ($product->Product_ID == 61) {
                    \Log::info("Product 61 variants:", ['count' => $variants->count(), 'data' => $variants->toArray()]);
                }
                
                $product->variants = $variants;
            }
            
            return response()->json($products, 200, [], JSON_PRETTY_PRINT);
            
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Lấy chi tiết sản phẩm theo ID
     */
    public function show($id)
    {
        $product = Product::with(['category', 'images', 'variants'])->findOrFail($id);
        return response()->json($product, 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * Lấy chi tiết sản phẩm theo slug
     */
    public function getProductBySlug($slug)
    {
        // SỬA DÒNG 68: wherenpm -> where
        $product = Product::with(['category', 'images', 'variants'])->where('slug', $slug)->first();
        if (!$product) {
            return response()->json(['message' => 'Không tìm thấy sản phẩm'], 404);
        }
        return response()->json($product, 200, [], JSON_PRETTY_PRINT);
    }

    public function rateProduct(Request $request, $productId)
    {
        $request->validate([
            'User_ID' => 'required|exists:users,ID',
            'Rating' => 'required|integer|min:1|max:5',
        ]);

        $rating = \App\Models\ProductRating::updateOrCreate(
            ['Product_ID' => $productId, 'User_ID' => $request->User_ID],
            ['Rating' => $request->Rating]
        );

        return response()->json($rating, 201);
    }

    public function getRatings($productId)
    {
        $avg = \App\Models\ProductRating::where('Product_ID', $productId)->avg('Rating');
        $count = \App\Models\ProductRating::where('Product_ID', $productId)->count();
        return response()->json(['avg' => $avg ?? 0, 'count' => $count]);
    }

    /**
     * Tìm kiếm sản phẩm theo tên (gợi ý cho ô tìm kiếm)
     * Trả về tối đa 8 sản phẩm có tên chứa từ khóa, sắp xếp mới nhất
     */
    public function search(Request $request)
    {
        $search = $request->input('search');
        $query = Product::query();
        if ($search) {
            $query->where('Name', 'LIKE', '%' . $search . '%');
        }
        $products = $query->orderBy('Product_ID', 'desc')->get();

        return response()->json(['data' => $products]);
    }
}
