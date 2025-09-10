<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Http;

class ProductApiController extends Controller
{
    /**
     * Lấy danh sách sản phẩm có phân trang (12 sản phẩm mỗi trang)
     */
    public function index()
    {
        $query = Product::with(['category', 'variants']);

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

        // Lọc theo giá (bạn cần bổ sung xử lý ở đây nếu muốn lọc theo giá)
        if (request()->has('price')) {
            // Giả sử FE truyền dạng: "Dưới 500.000đ", "500.000đ - 1.000.000đ", "Trên 2.000.000đ"
            $prices = explode(',', request('price'));
            $query->where(function($q) use ($prices) {
                foreach ($prices as $price) {
                    $price = trim($price);
                    if ($price == "Dưới 500.000đ") {
                        $q->orWhere('Price', '<', 500000);
                    } elseif ($price == "500.000đ - 1.000.000đ") {
                        $q->orWhereBetween('Price', [500000, 1000000]);
                    } elseif ($price == "1.000.000đ - 2.000.000đ") {
                        $q->orWhereBetween('Price', [1000000, 2000000]);
                    } elseif ($price == "Trên 2.000.000đ") {
                        $q->orWhere('Price', '>', 2000000);
                    }
                }
            });
        }

        // Lọc theo biến thể (ví dụ: weight, stiffness, balance)
        if (request()->has('weight')) {
            $query->whereHas('variants', function($q) {
                $q->where('weight', request('weight'));
            });
        }
        if (request()->has('stiffness')) {
            $query->whereHas('variants', function($q) {
                $q->where('stiffness', request('stiffness'));
            });
        }
        if (request()->has('balance')) {
            $query->whereHas('variants', function($q) {
                $q->where('balance', request('balance'));
            });
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(12);

        // Thêm rating trung bình cho từng sản phẩm
        $products->getCollection()->transform(function ($product) {
            $product->rating = \App\Models\ProductRating::where('Product_ID', $product->Product_ID)->avg('Rating') ?? 0;
            $product->total_quantity = $product->total_quantity;
            return $product;
        });

        return response()->json($products, 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * Lấy chi tiết sản phẩm theo ID
     */
    public function show($id)
    {
        $product = Product::with(['category', 'images', 'variants'])->findOrFail($id);
        $product->total_quantity = $product->total_quantity; // Thêm dòng này
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
        $product->total_quantity = $product->total_quantity; // Thêm dòng này
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

    public function chatbot(Request $request)
    {
        $question = $request->input('question');
        if (!$question) {
            return response()->json(['message' => 'Thiếu câu hỏi'], 400);
        }

        // Lấy tất cả sản phẩm
        $allProducts = \App\Models\Product::get(['Name', 'Brand', 'Price', 'Description', 'Image']);

        // Xác định loại sản phẩm khách hỏi
        $typeKeywords = [
            'vợt' => ['vợt', 'vot'],
            'giày' => ['giày', 'giay'],
            'áo' => ['áo', 'ao'],
            'quần' => ['quần', 'quan'],
            'phụ kiện' => ['phụ kiện', 'phu kien', 'băng', 'bao', 'vớ', 'dây', 'túi', 'khăn', 'kẹp', 'tay nắm'],
        ];

        $type = null;
        foreach ($typeKeywords as $t => $arr) {
            foreach ($arr as $kw) {
                if (stripos($question, $kw) !== false) {
                    $type = $t;
                    break 2;
                }
            }
        }

        // Xác định thương hiệu khách hỏi
        $brands = ['yonex', 'lining', 'li-ning', 'victor', 'mizuno'];
        $brand = null;
        foreach ($brands as $b) {
            if (stripos($question, $b) !== false) {
                $brand = $b;
                break;
            }
        }

        // Lọc sản phẩm đúng loại và brand nếu có
        $filtered = $allProducts->filter(function($p) use ($type, $brand) {
            $ok = true;
            if ($type) {
                $ok = stripos($p->Name, $type) !== false;
            }
            if ($ok && $brand) {
                $ok = stripos(strtolower($p->Brand), strtolower($brand)) !== false;
            }
            return $ok;
        })->values();

        // Nếu có lọc được sản phẩm phù hợp, chỉ truyền danh sách này vào Gemini
        if ($filtered->count() > 0) {
            $productList = $filtered->map(function($p) {
                $imgUrl = $p->Image ? url('storage/' . ltrim($p->Image, '/')) : '';
                return "- Tên: {$p->Name}\n  Thương hiệu: {$p->Brand}\n  Giá: {$p->Price}đ\n  Ảnh: {$imgUrl}\n  Mô tả: {$p->Description}";
            })->implode("\n\n");
        } else {
            $productList = '';
        }

        $prompt = <<<EOT
Bạn là trợ lý bán hàng cầu lông Vicnex. 
Nhiệm vụ của bạn là tư vấn, giới thiệu sản phẩm cầu lông dựa trên kho sản phẩm thực tế bên dưới. 
Bạn chỉ được phép trả lời dựa trên dữ liệu sản phẩm được cung cấp, không tự bịa ra sản phẩm hoặc thông tin không có trong danh sách.

1. Nếu khách hỏi về một sản phẩm cụ thể (ví dụ: "có vợt Yonex Astrox 100ZZ không?"), hãy kiểm tra tên sản phẩm hoặc thương hiệu trong danh sách. Nếu có, xác nhận shop có sản phẩm đó, hỏi khách có muốn xem chi tiết không. Nếu khách xác nhận muốn xem chi tiết (câu hỏi có từ "xem", "chi tiết", "giá", "hình", "ảnh", "show"...), hãy trả về thông tin chi tiết: tên, thương hiệu, giá, mô tả, và ảnh sản phẩm (dùng markdown ![ảnh](link)). Nếu không có, trả lời lịch sự rằng shop chưa có sản phẩm đó.

2. Nếu khách hỏi về một thương hiệu (ví dụ: "shop có sản phẩm Yonex không?"), chỉ xác nhận nếu trong danh sách có sản phẩm thuộc thương hiệu đó. Nếu không có, trả lời lịch sự rằng shop chưa có sản phẩm thuộc thương hiệu đó.

3. Nếu khách hỏi về loại sản phẩm (vợt, giày, quần áo, phụ kiện...), hãy liệt kê các sản phẩm phù hợp trong danh sách, kèm tên, giá, thương hiệu. Nếu khách muốn xem chi tiết, hãy trả về thông tin chi tiết như trên.

4. Nếu khách hỏi về cách chọn sản phẩm (ví dụ: "tư vấn chọn vợt cho người mới chơi"), hãy tư vấn ngắn gọn, thân thiện, sau đó gợi ý một số sản phẩm phù hợp trong danh sách (nếu có).

5. Nếu khách hỏi tư vấn chọn vợt mà chưa cung cấp đủ thông tin (ví dụ: kinh nghiệm chơi, phong cách chơi, nhu cầu công/thủ, giới tính, độ tuổi...), hãy hỏi lại khách các câu như:
- Bạn đã chơi cầu lông lâu chưa? (người mới hay đã chơi lâu)
- Bạn thích lối đánh tấn công hay phòng thủ?
- Bạn thường chơi đơn hay đôi?
- Bạn muốn chọn vợt nhẹ hay vợt nặng?
- Bạn là nam hay nữ, bao nhiêu tuổi?
Sau khi khách trả lời, hãy tư vấn loại vợt phù hợp và gợi ý các sản phẩm trong danh sách nếu có.

6. Nếu khách hỏi về sản phẩm không có trong danh sách, trả lời lịch sự rằng shop chưa có sản phẩm đó.

7. Nếu khách hỏi về chủ đề không liên quan cầu lông, hãy lịch sự từ chối trả lời.

8. Nếu khách hỏi về bất kỳ loại sản phẩm nào có đặc tính cụ thể (ví dụ: vợt thiên về phòng thủ, giày nhẹ, áo thấm hút mồ hôi, túi đựng nhiều vợt...), hãy so sánh đặc điểm của tất cả các sản phẩm trong danh sách với yêu cầu của khách (dựa vào mô tả, thông số, tính năng...) và chỉ gợi ý các sản phẩm phù hợp nhất. Không gợi ý các sản phẩm không đáp ứng đúng đặc tính khách yêu cầu.

Nếu khách hỏi về giày của một thương hiệu (ví dụ: "giày Lining"), chỉ liệt kê các sản phẩm là giày và thương hiệu đó. Nếu không có, trả lời lịch sự rằng shop chưa có giày của thương hiệu này.

Khi khách muốn xem chi tiết sản phẩm, chỉ trả về:
- Ảnh sản phẩm (dùng markdown ![ảnh](link))
- Tên sản phẩm
- Giá sản phẩm
- Một mô tả ngắn gọn (không quá 2 dòng, tập trung vào điểm nổi bật hoặc lợi ích chính)

Không cần liệt kê lại thương hiệu nếu tên đã có thương hiệu. Không cần trả về toàn bộ mô tả dài, ưu đãi, chính sách, chỉ cần thông tin ngắn gọn, dễ nhìn.

Ví dụ:
---
![ảnh](http://localhost:8000/storage/uploads/products/1754638613_Thiết kế chưa có tên (1).png)
**Vợt cầu lông Yonex Astrox 100ZZ**  
Giá: 3.500.000đ  
Vợt tấn công cao cấp, smash mạnh, kiểm soát tốt, phù hợp người chơi chuyên nghiệp.
---

Nếu khách hỏi nhiều sản phẩm, trình bày mỗi sản phẩm theo mẫu trên, mỗi sản phẩm cách nhau một dòng trống.

**Dưới đây là danh sách sản phẩm hiện có:**
$productList

**Khách hỏi:** "$question"

**Lưu ý:**
- Chỉ trả lời dựa trên dữ liệu thực tế.
- Không bịa ra sản phẩm, giá, thương hiệu, mô tả hoặc hình ảnh không có trong danh sách.
- Khi show sản phẩm, trình bày rõ ràng: tên, thương hiệu, giá, mô tả, ảnh (dùng markdown ![ảnh](link)).
- Luôn trả lời ngắn gọn, thân thiện, chuyên nghiệp.

**QUAN TRỌNG:**
Khi liệt kê sản phẩm, hãy trình bày mỗi sản phẩm trên một dòng, có số thứ tự rõ ràng, ví dụ:
Dạ, shop đang có những mẫu này ạ:
1. Vợt cầu lông Yonex Astrox 100ZZ
2. Vợt cầu lông Yonex Nanoflare 800
3. Vợt cầu lông Yonex Voltric Z-Force II
...
Tuyệt đối không trả lời danh sách sản phẩm dính liền trên một dòng, không dùng dấu phẩy để ngăn cách các sản phẩm.
EOT;

        $response = Http::post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=AIzaSyAmjQBWLI8aDYFeekvKuEycmlW0TEW4DdU", [
            "contents" => [
                [
                    "parts" => [
                        ["text" => $prompt]
                    ]
                ]
            ]
        ]);

        $data = $response->json();
        $answer = $data['candidates'][0]['content']['parts'][0]['text'] ?? "Xin lỗi, tôi chưa có câu trả lời phù hợp.";

        return response()->json([
            'answer' => $answer,
            'products' => []
        ]);
    }
}
