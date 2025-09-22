<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductAttribute;
use App\Models\ProductValue;
use App\Models\ProductVariant;
use App\Models\Brand;
use App\Models\ProductLine;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // THÊM EAGER LOADING PRODUCTLINE
        $query = Product::with(['category', 'productLine', 'variants.values.attribute']);

        // Debug: Log các tham số request
        Log::info('Filter parameters:', $request->all());

        // Tìm kiếm theo từ khóa (tên, mô tả, SKU, thương hiệu) - hoạt động độc lập
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('Name', 'like', '%' . $keyword . '%')
                  ->orWhere('Description', 'like', '%' . $keyword . '%')
                  ->orWhere('SKU', 'like', '%' . $keyword . '%')
                  ->orWhere('Brand', 'like', '%' . $keyword . '%');
            });
        }

        // Lọc theo danh mục - hoạt động độc lập
        if ($request->filled('category')) {
            $query->where('Categories_ID', $request->category);
        }

        // Lọc theo thương hiệu - hoạt động độc lập
        if ($request->filled('brand')) {
            $query->where('Brand', 'like', '%' . $request->brand . '%');
        }

        // Lọc theo trạng thái - hoạt động độc lập
        if ($request->filled('status')) {
            $query->where('Status', $request->status);
        }

        // Lọc theo khoảng giá - đơn giản hóa logic
        if ($request->filled('price_min')) {
            $query->where('Price', '>=', $request->price_min);
        }

        if ($request->filled('price_max')) {
            $query->where('Price', '<=', $request->price_max);
        }

        // Lọc theo số lượng tồn kho - hoạt động độc lập
        if ($request->filled('quantity_min')) {
            $query->where('Quantity', '>=', $request->quantity_min);
        }

        if ($request->filled('quantity_max')) {
            $query->where('Quantity', '<=', $request->quantity_max);
        }

        // Lọc theo các flag đặc biệt - hoạt động độc lập
        if ($request->filled('is_featured')) {
            $query->where('is_featured', $request->is_featured);
        }

        if ($request->filled('is_hot')) {
            $query->where('is_hot', $request->is_hot);
        }

        if ($request->filled('is_best_seller')) {
            $query->where('is_best_seller', $request->is_best_seller);
        }

        // Lọc theo sản phẩm có khuyến mãi - hoạt động độc lập
        if ($request->filled('has_discount')) {
            if ($request->has_discount == '1') {
                $query->whereNotNull('Discount_price')->where('Discount_price', '>', 0);
            } else {
                $query->where(function($q) {
                    $q->whereNull('Discount_price')->orWhere('Discount_price', '<=', 0);
                });
            }
        }

        // Lọc theo sản phẩm có biến thể - hoạt động độc lập
        if ($request->filled('has_variants')) {
            if ($request->has_variants == '1') {
                $query->whereHas('variants');
            } else {
                $query->whereDoesntHave('variants');
            }
        }

        // Lọc theo các thuộc tính biến thể
        if ($request->filled('variant_weight')) {
            $weights = explode(',', $request->variant_weight);
            $query->whereHas('variants.values', function($q) use ($weights) {
                $q->whereHas('attribute', function($q) {
                    $q->where('Name', 'Trọng lượng');
                })->whereIn('Value', $weights);
            });
        }

        if ($request->filled('variant_stiffness')) {
            $stiffness = explode(',', $request->variant_stiffness);
            $query->whereHas('variants.values', function($q) use ($stiffness) {
                $q->whereHas('attribute', function($q) {
                    $q->where('Name', 'Độ cứng thân');
                })->whereIn('Value', $stiffness);
            });
        }

        if ($request->filled('variant_balance')) {
            $balance = explode(',', $request->variant_balance);
            $query->whereHas('variants.values', function($q) use ($balance) {
                $q->whereHas('attribute', function($q) {
                    $q->where('Name', 'Điểm cân bằng');
                })->whereIn('Value', $balance);
            });
        }

        if ($request->filled('variant_size')) {
            $sizes = explode(',', $request->variant_size);
            $query->whereHas('variants.values', function($q) use ($sizes) {
                $q->whereHas('attribute', function($q) {
                    $q->where('Name', 'Kích cỡ');
                })->whereIn('Value', $sizes);
            });
        }

        if ($request->filled('variant_color')) {
            $colors = explode(',', $request->variant_color);
            $query->whereHas('variants.values', function($q) use ($colors) {
                $q->whereHas('attribute', function($q) {
                    $q->where('Name', 'Màu sắc');
                })->whereIn('Value', $colors);
            });
        }

        if ($request->filled('variant_gender')) {
            $genders = explode(',', $request->variant_gender);
            $query->whereHas('variants.values', function($q) use ($genders) {
                $q->whereHas('attribute', function($q) {
                    $q->where('Name', 'Giới tính');
                })->whereIn('Value', $genders);
            });
        }

        // Sắp xếp (mặc định: mới thêm gần đây nhất)
        $sortBy = $request->get('sort_by', 'Created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        // Validate sort_by để tránh SQL injection
        $allowedSortFields = ['Product_ID', 'Name', 'Price', 'Discount_price', 'Quantity', 'Brand', 'Created_at', 'Updated_at'];
        if (!in_array($sortBy, $allowedSortFields)) {
            $sortBy = 'Updated_at';
        }

        $query->orderBy($sortBy, $sortOrder);

        // Debug: Log SQL query
        Log::info('SQL Query:', ['sql' => $query->toSql(), 'bindings' => $query->getBindings()]);

        $products = $query->paginate(10)->appends($request->query());
        $categories = Category::all();

        // Debug: Log kết quả
        Log::info('Results:', ['total' => $products->total(), 'count' => $products->count()]);

        // Thêm thông tin debug vào view
        $debugInfo = [
            'total_products' => Product::count(),
            'filter_params' => $request->all(),
            'sql_query' => $query->toSql(),
            'sql_bindings' => $query->getBindings()
        ];

        return view('admin.products.index', compact('products', 'categories', 'debugInfo'));
    }

    public function create()
    {
        $categories = Category::all();
        
        // SỬA: ĐẢM BẢO CÓ BRANDS DATA
        $brands = Brand::all();
        
        // NẾU CHƯA CÓ BẢNG BRANDS, TẠO TẠM TỪ PRODUCTS
        if ($brands->isEmpty()) {
            $brands = collect(\DB::table('products')
                ->select('Brand as name')
                ->distinct()
                ->whereNotNull('Brand')
                ->where('Brand', '!=', '')
                ->get()
                ->map(function($item, $index) {
                    return (object)[
                        'id' => $index + 1,
                        'name' => $item->name
                    ];
                }));
        }
        
        $attributes = ProductAttribute::with('values')->get();
        
        // THÊM DÒNG NÀY:
        $productLines = ProductLine::all();
        
        // CẬP NHẬT COMPACT:
        return view('admin.products.create', compact('categories', 'brands', 'attributes', 'productLines'));
    }

    public function store(Request $request)
    {
        Log::info('Product store method called', $request->all());

        try {
            Log::info('Starting validation...');
            $validated = $request->validate([
                'Categories_ID' => 'required|exists:categories,Categories_ID',
                'Name' => 'required|string|max:255',
                'SKU' => 'nullable|string|max:100|unique:products,SKU',
                'Brand' => 'nullable|string|max:255',
                'brand_id' => 'nullable|exists:brands,id', // THÊM VALIDATION
                'product_line_id' => 'nullable|exists:product_lines,id', // THÊM VALIDATION
                'Description' => 'nullable|string',
                'Image' => 'nullable|image|max:2048',
                'Images.*' => 'nullable|image|max:2048',
                'Price' => 'required|numeric|min:0',
                'Discount_price' => 'nullable|numeric|min:0|lt:Price',
                'Quantity' => 'required|numeric|min:0',
                'Status' => 'nullable|boolean',
            ]);

            Log::info('Validation passed successfully');

            // XỬ LÝ BRAND NAME TỪ BRAND_ID
            if ($request->filled('brand_id')) {
                $brand = Brand::find($request->brand_id);
                $validated['Brand'] = $brand ? $brand->name : null;
            }

            // XỬ LÝ PRODUCT_LINE_ID
            if ($request->filled('product_line_id')) {
                $validated['product_line_id'] = $request->product_line_id;
            }

            if ($request->hasFile('Image')) {
                $file = $request->file('Image');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/products'), $fileName);
                $validated['Image'] = 'uploads/products/' . $fileName;
            }

            $validated['Status'] = $request->has('Status') ? 1 : 0;

            // Chỉ thêm các trường nếu chúng tồn tại trong database
            if ($request->has('is_featured')) {
                $validated['is_featured'] = 1;
            }
            if ($request->has('is_hot')) {
                $validated['is_hot'] = 1;
            }
            if ($request->has('is_best_seller')) {
                $validated['is_best_seller'] = 1;
            }

            // Chỉ thêm details nếu có giá trị
            if ($request->filled('details')) {
                $validated['details'] = $request->input('details');
            }

            // Đảm bảo Quantity là integer
            $validated['Quantity'] = (int) $validated['Quantity'];

            // Thêm timestamp
            $validated['Created_at'] = now();
            $validated['Updated_at'] = now();

            // Log validated data for debugging
            Log::info('Validated product data:', $validated);

            // Tạo sản phẩm chính
            $product = Product::create($validated);
            Log::info('Product created', ['product_id' => $product->Product_ID, 'name' => $product->Name]);

            // Lưu nhiều biến thể nếu có
            $totalQty = 0;
            if ($request->has('variants')) {
                foreach ($request->input('variants', []) as $variantData) {
                    if (empty($variantData['enabled'])) continue; // Bỏ qua biến thể không được tích

                    if (!empty($variantData['Quantity'])) {
                        $totalQty += (int)$variantData['Quantity'];
                    }
                    // Bỏ qua nếu không nhập SKU hoặc Variant_name
                    if (empty($variantData['SKU']) || empty($variantData['Variant_name'])) {
                        continue;
                    }

                    // Lấy tên sản phẩm gốc
                    $baseName = $product->Name;

                    // Lấy tên các giá trị thuộc tính đã chọn
                    $valueNames = [];
                    if (!empty($variantData['Values_IDs'])) {
                        $valueNames = \App\Models\ProductValue::whereIn('Values_ID', $variantData['Values_IDs'])->pluck('Value')->toArray();
                    }

                    // Tạo tên biến thể: Tên sản phẩm + các giá trị thuộc tính
                    $variantName = $variantData['Variant_name'] ?? ($baseName . (count($valueNames) ? ' - ' . implode(' - ', $valueNames) : ''));

                    $baseSku = $variantData['SKU'];
                    $sku = $baseSku;
                    $suffix = 1;
                    while (\App\Models\ProductVariant::where('SKU', $sku)->exists()) {
                        $sku = $baseSku . '-' . $suffix;
                        $suffix++;
                    }

                    $variant = $product->variants()->create([
                        'SKU' => $sku,
                        'Variant_name' => $variantName,
                        'Price' => $variantData['Price'] ?? $product->Price,
                        'Discount_price' => $variantData['Discount_price'] ?? $product->Discount_price,
                        'Quantity' => $variantData['Quantity'] ?? $product->Quantity,
                        'Status' => 1,
                        'Created_at' => now(),
                        'Updated_at' => now(),
                    ]);
                    $variant->values()->attach($variantData['Values_IDs'] ?? []);
                }
                // ✅ Cập nhật lại số lượng sản phẩm cha
                if ($totalQty > 0) {
                    $product->update(['Quantity' => $totalQty]);
                }
            }

            if ($request->hasFile('Images')) {
                foreach ($request->file('Images') as $img) {
                    $fileName = time() . '_' . $img->getClientOriginalName();
                    $img->move(public_path('uploads/products/gallery'), $fileName);
                    $product->images()->create([
                        'Image_path' => 'uploads/products/gallery/' . $fileName
                    ]);
                }
            }

            Log::info('Product store completed successfully', ['product_id' => $product->Product_ID]);

            $successMessage = "Thêm sản phẩm thành công!";
            $successMessage .= " Sản phẩm: " . $product->Name;
            
            // THÊM THÔNG TIN PRODUCT LINE VÀO SUCCESS MESSAGE
            if ($product->productLine) {
                $successMessage .= " (Dòng: " . $product->productLine->name . ")";
            }
            
            $successMessage .= " (ID: " . $product->Product_ID . ")";

            if ($request->has('variants')) {
                $variantCount = count(array_filter($request->input('variants', []), function($v) {
                    return !empty($v['enabled']);
                }));
                if ($variantCount > 0) {
                    $successMessage .= " với " . $variantCount . " biến thể";
                }
            }

            return redirect()->route('admin.products.index')->with('success', $successMessage);

        } catch (\Exception $e) {
            Log::error('Error creating product', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);

            $errorMessage = 'Có lỗi xảy ra khi tạo sản phẩm: ';
            if (strpos($e->getMessage(), 'file_get_contents') !== false) {
                $errorMessage .= 'Lỗi khi xử lý file ảnh. Vui lòng kiểm tra lại file ảnh.';
            } elseif (strpos($e->getMessage(), 'Permission denied') !== false) {
                $errorMessage .= 'Không có quyền ghi file. Vui lòng kiểm tra quyền thư mục upload.';
            } else {
                $errorMessage .= $e->getMessage();
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $errorMessage);
        }
    }

    public function edit($id)
    {
        // THÊM EAGER LOADING PRODUCTLINE
        $product = Product::with(['variants', 'images', 'productLine'])->findOrFail($id);
        $categories = Category::all();
        
        // ĐẢM BẢO CÓ BRANDS DATA
        $brands = Brand::all();
        if ($brands->isEmpty()) {
            $brands = collect(\DB::table('products')
                ->select('Brand as name')
                ->distinct()
                ->whereNotNull('Brand')
                ->where('Brand', '!=', '')
                ->get()
                ->map(function($item, $index) {
                    return (object)[
                        'id' => $index + 1,
                        'name' => $item->name
                    ];
                }));
        }
        
        $attributes = ProductAttribute::with('values')->get();
        
        // THÊM DÒNG NÀY:
        $productLines = ProductLine::all();
        
        $variantSKU = optional($product->variant)->SKU;

        // LẤY SELECTED ATTRIBUTE VALUES
        $selectedValues = [];
        foreach ($product->variants as $variant) {
            $parts = explode(' - ', $variant->Variant_name);
            foreach ($attributes as $index => $attribute) {
                if (isset($parts[$index])) {
                    $selectedValues[$attribute->Name][] = $parts[$index];
                }
            }
        }

        // Remove duplicates
        foreach ($selectedValues as $key => $values) {
            $selectedValues[$key] = array_unique($values);
        }

        // CẬP NHẬT COMPACT:
        return view('admin.products.edit', compact('product', 'categories', 'brands', 'attributes', 'productLines', 'variantSKU', 'selectedValues'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        
        $validated = $request->validate([
            'Categories_ID' => 'required|exists:categories,Categories_ID',
            'Name' => 'required|string|max:255',
            'SKU' => 'nullable|string|max:100',
            'Description' => 'nullable|string',
            'details' => 'nullable|string',
            'Image' => 'nullable|image|max:2048',
            'Images.*' => 'nullable|image|max:2048',
            'Price' => 'required|numeric|min:0',
            'Discount_price' => 'nullable|numeric|min:0|lt:Price',
            'Quantity' => 'required|integer|min:0',
            'Status' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'is_hot' => 'nullable|boolean',
            'is_best_seller' => 'nullable|boolean',
            'brand_id' => 'required|exists:brands,id',
            'product_line_id' => 'nullable|exists:product_lines,id', // VALIDATION CHO PRODUCT LINE
        ]);

        // XỬ LÝ BRAND
        $brand = Brand::find($request->brand_id);
        $validated['Brand'] = $brand ? $brand->name : null;

        // XỬ LÝ PRODUCT LINE
        if ($request->filled('product_line_id')) {
            $validated['product_line_id'] = $request->product_line_id;
        } else {
            $validated['product_line_id'] = null;
        }

        if ($request->hasFile('Image')) {
            $file = $request->file('Image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/products'), $fileName);
            $validated['Image'] = 'uploads/products/' . $fileName;
        }

        $validated['Status'] = $request->has('Status') ? 1 : 0;
        $validated['is_featured'] = $request->has('is_featured') ? 1 : 0;
        $validated['is_hot'] = $request->has('is_hot') ? 1 : 0;
        $validated['is_best_seller'] = $request->has('is_best_seller') ? 1 : 0;
        $validated['Updated_at'] = now();
        $validated['details'] = $request->input('details');

        // Cập nhật slug nếu tên thay đổi
        if ($product->Name !== $validated['Name']) {
            $validated['slug'] = Str::slug($validated['Name']);
        }

        $product->update($validated);

        // --- Xử lý biến thể: update, tạo mới, xóa biến thể cũ không còn trong form ---
        $oldVariantIds = $product->variants->pluck('Variant_ID')->toArray();
        $newVariantIds = [];

        if ($request->has('variants')) {
            foreach ($request->input('variants', []) as $variantData) {
                if (empty($variantData['enabled'])) continue; // Bỏ qua biến thể không được tích

                // Bỏ qua nếu không nhập SKU hoặc Variant_name
                if (empty($variantData['SKU']) || empty($variantData['Variant_name'])) {
                    continue;
                }

                // Sinh tên biến thể nếu không nhập
                $baseName = $product->Name;
                $valueNames = [];
                if (!empty($variantData['Values_IDs'])) {
                    $valueNames = \App\Models\ProductValue::whereIn('Values_ID', $variantData['Values_IDs'])->pluck('Value')->toArray();
                }
                $variantName = $variantData['Variant_name'] ?? ($baseName . (count($valueNames) ? ' - ' . implode(' - ', $valueNames) : ''));

                // Sinh SKU không trùng
                $baseSku = $variantData['SKU'];
                $sku = $baseSku;
                $suffix = 1;
                while (\App\Models\ProductVariant::where('SKU', $sku)
                    ->when(!empty($variantData['Variant_ID']), function($q) use ($variantData) {
                        $q->where('Variant_ID', '!=', $variantData['Variant_ID']);
                    })->exists()) {
                    $sku = $baseSku . '-' . $suffix;
                    $suffix++;
                }

                if (!empty($variantData['Variant_ID'])) {
                    // Update biến thể cũ
                    $newVariantIds[] = $variantData['Variant_ID'];
                    $variant = ProductVariant::find($variantData['Variant_ID']);
                    if ($variant) {
                        $variant->update([
                            'SKU' => $sku,
                            'Variant_name' => $variantName,
                            'Price' => $variantData['Price'] ?? $product->Price,
                            'Discount_price' => $variantData['Discount_price'] ?? $product->Discount_price,
                            'Quantity' => $variantData['Quantity'] ?? $product->Quantity,
                            'Status' => 1,
                            'Updated_at' => now(),
                        ]);
                        $variant->values()->sync($variantData['Values_IDs'] ?? []);
                    }
                } else {
                    // Tạo mới biến thể
                    $variant = $product->variants()->create([
                        'SKU' => $sku,
                        'Variant_name' => $variantName,
                        'Price' => $variantData['Price'] ?? $product->Price,
                        'Discount_price' => $variantData['Discount_price'] ?? $product->Discount_price,
                        'Quantity' => $variantData['Quantity'] ?? $product->Quantity,
                        'Status' => 1,
                        'Created_at' => now(),
                        'Updated_at' => now(),
                    ]);
                    $variant->values()->attach($variantData['Values_IDs'] ?? []);
                    $newVariantIds[] = $variant->Variant_ID;
                }
            }
        }

        // Xóa các biến thể cũ không còn trong form
        $toDelete = array_diff($oldVariantIds, $newVariantIds);
        if (!empty($toDelete)) {
            ProductVariant::whereIn('Variant_ID', $toDelete)->delete();
        }
        // ✅ Cập nhật lại số lượng sản phẩm cha dựa vào biến thể
        if ($product->variants()->exists()) {
            $totalQty = $product->variants()->sum('Quantity');
            $product->update(['Quantity' => $totalQty]);
        }

        $successMessage = "Cập nhật sản phẩm thành công!";
        $successMessage .= " Sản phẩm: " . $product->Name;
        
        // THÊM THÔNG TIN PRODUCT LINE VÀO SUCCESS MESSAGE
        if ($product->productLine) {
            $successMessage .= " (Dòng: " . $product->productLine->name . ")";
        }
        
        $successMessage .= " (ID: " . $product->Product_ID . ")";

        if ($request->has('variants')) {
            $variantCount = count(array_filter($request->input('variants', []), function($v) {
                return !empty($v['enabled']);
            }));
            if ($variantCount > 0) {
                $successMessage .= " với " . $variantCount . " biến thể";
            }
        }

        return redirect()->route('admin.products.index')->with('success', $successMessage);
    }

    public function destroy($id)
{
    $product = Product::findOrFail($id);

    // Kiểm tra nếu sản phẩm hoặc biến thể của nó đã có trong đơn hàng
    if ($product->orderDetails()->exists() || $product->variants()->whereHas('orderDetails')->exists()) {
        return redirect()->route('admin.products.index')
            ->with('error', 'Sản phẩm hoặc biến thể của sản phẩm đã được bán, không thể xóa!');
    }

    // Xóa ảnh chính
    if ($product->Image && file_exists(public_path($product->Image))) {
        unlink(public_path($product->Image));
    }

    // Xóa ảnh phụ
    foreach ($product->images as $image) {
        if ($image->Image_path && file_exists(public_path($image->Image_path))) {
            unlink(public_path($image->Image_path));
        }
        $image->delete();
    }

    // Xóa các biến thể trước khi xóa sản phẩm
    foreach ($product->variants as $variant) {
        $variant->delete();
    }

    // Xóa sản phẩm
    $product->delete();

    return redirect()->route('admin.products.index')->with('success', 'Xóa sản phẩm thành công!');
}


    public function deleteImage($id)
    {
        $image = \App\Models\ProductImage::find($id);
        if ($image) {
            if (file_exists(public_path($image->Image_path))) {
                unlink(public_path($image->Image_path));
            }
            $image->delete();
        }
        return back()->with('success', 'Đã xóa ảnh phụ!');
    }

    public function show($id)
    {
        // THÊM EAGER LOADING PRODUCTLINE
        $product = Product::with(['category', 'productLine', 'variants.values.attribute', 'images'])->findOrFail($id);

        // Tăng lượt xem mỗi lần xem chi tiết (nếu muốn)
        $product->increment('View');

        return view('admin.products.show', compact('product'));
    }

    /**
     * Lấy product lines theo brand ID
     */
    public function getProductLinesByBrand($brandId)
    {
        try {
            // Tìm brand theo ID từ bảng brands
            $brand = \DB::table('brands')->where('id', $brandId)->first();
            
            if (!$brand) {
                return response()->json(['error' => 'Brand not found'], 404);
            }
            
            // Log để debug
            \Log::info("Getting product lines for brand:", ['brand_id' => $brandId, 'brand_name' => $brand->name]);
            
            // Lấy product lines theo brand name
            $productLines = \DB::table('product_lines')
                ->where('brand', $brand->name)
                ->where('is_active', 1)
                ->orderBy('name')
                ->select('id', 'name', 'description')
                ->get();
            
            \Log::info("Found product lines:", ['count' => $productLines->count(), 'lines' => $productLines->toArray()]);
            
            return response()->json($productLines);
            
        } catch (\Exception $e) {
            \Log::error('Error getting product lines by brand:', [
                'error' => $e->getMessage(), 
                'brand_id' => $brandId,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
