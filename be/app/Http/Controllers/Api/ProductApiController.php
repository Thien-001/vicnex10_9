<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductRating;
use Illuminate\Support\Facades\Log;

class ProductApiController extends Controller
{
    /**
     * Lấy danh sách sản phẩm có phân trang (12 sản phẩm mỗi trang)
     */
    public function index(Request $request)
    {
        try {
            // SỬ DỤNG EAGER LOADING VỚI PRODUCTLINE
            $query = Product::with(['category', 'productLine', 'ratings']);
            
            // Filter theo category
            if ($request->has('Categories_ID')) {
                $query->where('Categories_ID', $request->Categories_ID);
            }
            
            // Filter theo brand
            if ($request->has('brand')) {
                $brands = explode(',', $request->brand);
                $query->whereIn('Brand', $brands);
            }
            
            // THÊM: Filter theo product line
            if ($request->has('product_line')) {
                $lines = explode(',', $request->product_line);
                $query->whereHas('productLine', function($q) use ($lines) {
                    $q->whereIn('name', $lines);
                });
            }
            
            // Special filters
            if ($request->has('is_sale')) {
                $query->whereNotNull('Discount_price')
                      ->whereColumn('Discount_price', '<', 'Price');
            }
            
            if ($request->has('is_best_seller')) {
                $query->where('is_best_seller', true);
            }
            
            if ($request->has('is_new')) {
                $query->where('Created_at', '>=', now()->subDays(30));
            }
            
            $products = $query->orderBy('Created_at', 'desc')->paginate(12);
            
            // Load variants cho từng product
            foreach ($products as $product) {
                $variants = \DB::table('product_variants')
                    ->where('Product_ID', $product->Product_ID)
                    ->get();
                
                $product->variants = $variants;
            }
            
            return response()->json($products, 200, [], JSON_PRETTY_PRINT);
            
        } catch (\Exception $e) {
            \Log::error("ProductApi Error:", ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Lấy chi tiết sản phẩm theo ID
     */
    public function show($id)
    {
        try {
            $product = Product::with(['category', 'images', 'variants'])->findOrFail($id);
            
            // SỬA: SỬ DỤNG MODEL PRODUCTRATING CHO DETAIL
            $ratings = ProductRating::where('Product_ID', $id)
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($rating) {
                    return [
                        'id' => $rating->id,
                        'Product_ID' => $rating->Product_ID,
                        'User_ID' => $rating->User_ID,
                        'Rating' => $rating->Rating,
                        'image' => $rating->image,
                        'text' => $rating->text,
                        'created_at' => $rating->created_at,
                        'updated_at' => $rating->updated_at,
                        'user_name' => $rating->user ? $rating->user->Name : 'Ẩn danh',
                        'user_avatar' => $rating->user ? $rating->user->Avatar : null,
                    ];
                });
            
            $product->ratings = $ratings;   
            
            return response()->json($product, 200, [], JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            \Log::error("ProductApi Show Error:", ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Lấy chi tiết sản phẩm theo slug
     */
    public function getProductBySlug($slug)
    {
        try {
            $product = Product::with(['category', 'images', 'variants'])->where('slug', $slug)->first();
            
            if (!$product) {
                return response()->json(['message' => 'Không tìm thấy sản phẩm'], 404);
            }
            
            // SỬA: SỬ DỤNG MODEL PRODUCTRATING CHO SLUG
            $ratings = ProductRating::where('Product_ID', $product->Product_ID)
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($rating) {
                    return [
                        'id' => $rating->id,
                        'Product_ID' => $rating->Product_ID,
                        'User_ID' => $rating->User_ID,
                        'Rating' => $rating->Rating,
                        'image' => $rating->image,
                        'text' => $rating->text,
                        'created_at' => $rating->created_at,
                        'updated_at' => $rating->updated_at,
                        'user_name' => $rating->user ? $rating->user->Name : 'Ẩn danh',
                        'user_avatar' => $rating->user ? $rating->user->Avatar : null,
                    ];
                });
            
            $product->ratings = $ratings;
            
            return response()->json($product, 200, [], JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            \Log::error("ProductApi GetBySlug Error:", ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // SỬA: SỬ DỤNG PRODUCTRATING CONTROLLER CHO RATING ENDPOINTS
    public function rateProduct(Request $request, $productId)
    {
        // Delegate tới ProductRatingController
        $controller = new \App\Http\Controllers\Api\ProductRatingController();
        return $controller->store($request, $productId);
    }

    public function getRatings($productId)
    {
        // Delegate tới ProductRatingController
        $controller = new \App\Http\Controllers\Api\ProductRatingController();
        return $controller->list($productId);
    }

    /**
     * Tìm kiếm sản phẩm theo tên (gợi ý cho ô tìm kiếm)
     * Trả về tối đa 8 sản phẩm có tên chứa từ khóa, sắp xếp mới nhất
     */
    public function search(Request $request)
    {
        try {
            $search = $request->input('search');
            $query = Product::with(['category']);
            
            if ($search) {
                $query->where('Name', 'LIKE', '%' . $search . '%');
            }
            
            $products = $query->orderBy('Product_ID', 'desc')->get();

            // SỬA: SỬ DỤNG MODEL PRODUCTRATING CHO SEARCH
            foreach ($products as $product) {
                $ratings = ProductRating::where('Product_ID', $product->Product_ID)
                    ->with('user')
                    ->orderBy('created_at', 'desc')
                    ->get()
                    ->map(function($rating) {
                        return [
                            'id' => $rating->id,
                            'Product_ID' => $rating->Product_ID,
                            'User_ID' => $rating->User_ID,
                            'Rating' => $rating->Rating,
                            'image' => $rating->image,
                            'text' => $rating->text,
                            'created_at' => $rating->created_at,
                            'updated_at' => $rating->updated_at,
                            'user_name' => $rating->user ? $rating->user->Name : 'Ẩn danh',
                            'user_avatar' => $rating->user ? $rating->user->Avatar : null,
                        ];
                    });
                
                $product->ratings = $ratings;
            }

            return response()->json(['data' => $products]);
        } catch (\Exception $e) {
            \Log::error("Search Error:", ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
