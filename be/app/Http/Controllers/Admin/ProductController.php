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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log; // Added Log facade

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'variants.values.attribute']);

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
        $brands = Brand::all();
        $weightAttribute = ProductAttribute::firstOrCreate(
            ['Name' => 'Trọng lượng'],
            ['Description' => 'Trọng lượng tiêu chuẩn của vợt cầu lông']
        );

        foreach (['5U', '4U', '3U'] as $value) {
            $weightAttribute->values()->firstOrCreate(['Value' => $value]);
        }

        $attributes = ProductAttribute::with('values')->get();

        return view('admin.products.create', compact('categories', 'brands', 'attributes'));
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
                'Description' => 'nullable|string',
                'Image' => 'nullable|image|max:2048',
                'Images.*' => 'nullable|image|max:2048',
                'Price' => 'required|numeric|min:0',
                'Discount_price' => 'nullable|numeric|min:0|lt:Price',
                'Quantity' => 'required|numeric|min:0',
                'Status' => 'nullable|boolean',
            ]);

            Log::info('Validation passed successfully');

            // Sau khi validate và trước khi lưu
            if ($request->filled('brand_id')) {
                $brand = \App\Models\Brand::find($request->brand_id);
                $validated['Brand'] = $brand ? $brand->name : null;
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

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error creating product', ['errors' => $e->errors()]);

            return redirect()->back()
                ->withInput()
                ->withErrors($e->errors())
                ->with('error', 'Có lỗi validation khi tạo sản phẩm. Vui lòng kiểm tra lại thông tin.');

        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Database error creating product', ['error' => $e->getMessage()]);

            $errorMessage = 'Lỗi database khi tạo sản phẩm: ';
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                $errorMessage .= 'SKU đã tồn tại trong hệ thống. Vui lòng sử dụng SKU khác.';
            } elseif (strpos($e->getMessage(), 'foreign key constraint') !== false) {
                $errorMessage .= 'Danh mục hoặc thương hiệu không tồn tại.';
            } else {
                $errorMessage .= $e->getMessage();
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $errorMessage);

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
        $product = Product::findOrFail($id);
        $categories = Category::all();
        $brands = Brand::all();
        $attributes = ProductAttribute::with('values')->get();
        $variantSKU = optional($product->variant)->SKU;

        return view('admin.products.edit', compact('product', 'categories', 'brands', 'attributes', 'variantSKU'));
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
        ]);

        $brand = Brand::find($request->brand_id);
        $validated['Brand'] = $brand ? $brand->name : null;
        $validated['brand_id'] = $brand ? $brand->id : null;

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

        $product->update($validated);

        if ($request->hasFile('Images')) {
            foreach ($request->file('Images') as $img) {
                $fileName = time() . '_' . $img->getClientOriginalName();
                $img->move(public_path('uploads/products/gallery'), $fileName);
                $product->images()->create([
                    'Image_path' => 'uploads/products/gallery/' . $fileName
                ]);
            }
        }

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

        if ($product->Image && file_exists(public_path($product->Image))) {
            unlink(public_path($product->Image));
        }

        foreach ($product->images as $image) {
            if ($image->Image_path && file_exists(public_path($image->Image_path))) {
                unlink(public_path($image->Image_path));
            }
            $image->delete();
        }

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
        $product = Product::with(['category', 'variants.values.attribute', 'images'])->findOrFail($id);

        // Tăng lượt xem mỗi lần xem chi tiết (nếu muốn)
        $product->increment('View');

        return view('admin.products.show', compact('product'));
    }
    
}
