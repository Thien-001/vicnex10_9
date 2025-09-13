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
        $query = Product::with(['category', 'variants']);

        // Debug: Log request parameters
        Log::info('Product API Filter Request:', request()->all());

        // Lọc theo Categories_ID (ưu tiên nếu có)
        if (request()->has('Categories_ID') && request('Categories_ID')) {
            $query->where('Categories_ID', request('Categories_ID'));
        }

        // Lọc theo tên danh mục (nếu FE truyền category)
        if (request()->has('category')) {
            $query->whereHas('category', function($q) {
                $q->where('Name', request('category'));
            });
        }

        // Lọc theo thương hiệu
        if (request()->has('brand')) {
            $brands = explode(',', request('brand'));
            $query->whereIn('Brand', $brands);
        }

        // Lọc theo giá
        if (request()->has('price')) {
            $prices = explode(',', request('price'));
            $query->where(function($q) use ($prices) {
                foreach ($prices as $price) {
                    $price = trim($price);
                    if ($price == "Dưới 500.000đ") {
                        $q->orWhere(function($subQ) {
                            $subQ->where('Price', '<', 500000)
                                 ->orWhere('Discount_price', '<', 500000);
                        });
                    } elseif ($price == "500.000đ - 1.000.000đ") {
                        $q->orWhere(function($subQ) {
                            $subQ->whereBetween('Price', [500000, 1000000])
                                 ->orWhereBetween('Discount_price', [500000, 1000000]);
                        });
                    } elseif ($price == "1.000.000đ - 2.000.000đ") {
                        $q->orWhere(function($subQ) {
                            $subQ->whereBetween('Price', [1000000, 2000000])
                                 ->orWhereBetween('Discount_price', [1000000, 2000000]);
                        });
                    } elseif ($price == "Trên 2.000.000đ") {
                        $q->orWhere(function($subQ) {
                            $subQ->where('Price', '>', 2000000)
                                 ->orWhere('Discount_price', '>', 2000000);
                        });
                    }
                }
            });
        }

        // Lọc theo biến thể thông qua giá trị thuộc tính
        if (request()->has('weight')) {
            $weights = explode(',', request('weight'));
            $query->whereHas('variants.values', function($q) use ($weights) {
                $q->whereHas('attribute', function($q) {
                    $q->where('Name', 'Trọng lượng');
                })->whereIn('Value', $weights);
            });
        }
        if (request()->has('stiffness')) {
            $stiffness = explode(',', request('stiffness'));
            $query->whereHas('variants.values', function($q) use ($stiffness) {
                $q->whereHas('attribute', function($q) {
                    $q->where('Name', 'Độ cứng thân');
                })->whereIn('Value', $stiffness);
            });
        }
        if (request()->has('balance')) {
            $balance = explode(',', request('balance'));
            $query->whereHas('variants.values', function($q) use ($balance) {
                $q->whereHas('attribute', function($q) {
                    $q->where('Name', 'Điểm cân bằng');
                })->whereIn('Value', $balance);
            });
        }
        if (request()->has('size')) {
            $sizes = explode(',', request('size'));
            $query->whereHas('variants.values', function($q) use ($sizes) {
                $q->whereHas('attribute', function($q) {
                    $q->where('Name', 'Kích cỡ');
                })->whereIn('Value', $sizes);
            });
        }
        if (request()->has('color')) {
            $colors = explode(',', request('color'));
            $query->whereHas('variants.values', function($q) use ($colors) {
                $q->whereHas('attribute', function($q) {
                    $q->where('Name', 'Màu sắc');
                })->whereIn('Value', $colors);
            });
        }
        if (request()->has('gender')) {
            $genders = explode(',', request('gender'));
            $query->whereHas('variants.values', function($q) use ($genders) {
                $q->whereHas('attribute', function($q) {
                    $q->where('Name', 'Giới tính');
                })->whereIn('Value', $genders);
            });
        }

        // Debug: Log final SQL query
        Log::info('Product API Final Query:', [
            'sql' => $query->toSql(),
            'bindings' => $query->getBindings()
        ]);

        $products = $query->orderBy('created_at', 'desc')->paginate(12);

        // Debug: Log result count
        Log::info('Product API Result:', [
            'total' => $products->total(),
            'count' => $products->count()
        ]);

        return response()->json($products, 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * Lấy chi tiết sản phẩm theo ID
     */
    public function show($id)
    {
        $product = Product::with(['category', 'images', 'variants'])->findOrFail($id); // Thêm 'images', 'variants'
        return response()->json($product, 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * Lấy chi tiết sản phẩm theo slug
     */
    public function getProductBySlug($slug)
    {
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

        // Lưu hoặc cập nhật đánh giá
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
}
