@extends('layouts.layout')

@section('content')



<style>
    /* Phần form lọc tổng thể */
.filter-form {
    background-color: #fdfdfd;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    font-size: 14px;
    border: 1px solid #e5e7eb;
}

/* Container sử dụng flex (nên để class riêng thay vì inline style) */
.filter-form > div {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    align-items: flex-end;
}

/* Nhóm label + input/select */
.filter-form div > label {
    display: block;
    font-weight: 500;
    margin-bottom: 5px;
    color: #333;
    font-size: 13px;
}

.filter-form input[type="text"],
.filter-form input[type="number"],
.filter-form select {
    width: 200px;
    padding: 8px 10px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    background-color: #fff;
    transition: all 0.3s ease;
    font-size: 13px;
}

.filter-form input:focus,
.filter-form select:focus {
    border-color: #3b82f6;
    outline: none;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.filter-form input::placeholder {
    color: #9ca3af;
    font-size: 12px;
}

/* Nút lọc và đặt lại */
.admin-form-loc {
    background: #3b82f6;
    color: #fff;
    border: none;
    border-radius: 6px;
    padding: 8px 16px;
    font-size: 13px;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
    display: inline-block;
    font-weight: 500;
}

.admin-form-loc:hover {
    background: #2563eb;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

/* Responsive cho form lọc */
@media (max-width: 768px) {
    .filter-form > div {
        flex-direction: column;
        gap: 15px;
    }

    .filter-form input[type="text"],
    .filter-form input[type="number"],
    .filter-form select {
        width: 100%;
    }
}

/* Bảng sản phẩm */
.table-product-list th, .table-product-list td {
    padding: 10px 8px;
    vertical-align: middle;
}
.table-product-list th {
    background: #f3f4f6;
    font-weight: 600;
    color: #222;
}
.table-product-list tr:nth-child(even) {
    background: #fafbfc;
}
.table-product-list img {
    border-radius: 6px;
    border: 1px solid #eee;
}
.action-buttons {
    /* display: flex; */
    gap: 8px;
    align-items: center;
}
.admin-button-table {
    background: #2563eb;
    color: #fff;
    border: none;
    border-radius: 5px;
    padding: 6px 14px;
    font-size: 15px;
    cursor: pointer;
    transition: background 0.2s;
}
.admin-button-table.btn-delete {
    background: #e11d48;
}
.admin-button-table:hover {
    background: #1d4ed8;
}
.admin-button-table.btn-delete:hover {
    background: #be123c;
}

/* Thống kê kết quả tìm kiếm */
.search-results-info {
    background: #f0f9ff;
    border: 1px solid #bae6fd;
    border-radius: 8px;
    padding: 12px 16px;
    margin-bottom: 20px;
    font-size: 14px;
    color: #0369a1;
}

.search-results-info strong {
    color: #0c4a6e;
}

/* CSS cho popup biến thể */
.variant-popup {
    animation: fadeIn 0.2s ease-in-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Đảm bảo link "Xem chi tiết" có thể click được */
.variant-detail-link {
    cursor: pointer !important;
    user-select: none;
    transition: color 0.2s ease;
    pointer-events: auto !important;
    position: relative;
    z-index: 10;
    display: inline-block !important;
    padding: 2px 4px !important;
    margin: 2px 0 !important;
}

.variant-detail-link:hover {
    color: #1d4ed8 !important;
    text-decoration: underline !important;
    background-color: rgba(37, 99, 235, 0.1) !important;
    border-radius: 4px !important;
}

.variant-detail-link:active {
    color: #1e40af !important;
    background-color: rgba(37, 99, 235, 0.2) !important;
}

/* Responsive cho popup */
@media (max-width: 768px) {
    .variant-popup {
        position: fixed !important;
        left: 10px !important;
        right: 10px !important;
        top: 50% !important;
        transform: translateY(-50%) !important;
        max-width: none !important;
        width: calc(100% - 20px) !important;
    }
}
</style>

<!-- =========================
     Tiêu đề và breadcrumb
============================ -->
<div class="head-title">
    <div class="left">
        <h1>Sản phẩm</h1>
        <ul class="breadcrumb">
            <li><a href="#">Sản phẩm</a></li>
            <li><i class='bx bx-chevron-right'></i></li>
            <li><a class="active" href="#">Danh sách sản phẩm</a></li>
        </ul>
    </div>
    <a href="{{ route('admin.products.create') }}" class="btn-download">
        <span class="text">+ Thêm sản phẩm mới</span>
    </a>
</div>
<!-- Hiển thị thông báo thành công -->
@if (session('success'))
    <div class="alert alert-success" style="background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
        <strong>Thành công!</strong> {{ session('success') }}
    </div>
@endif

<!-- Hiển thị thông báo lỗi -->
@if (session('error'))
    <div class="alert alert-danger" style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
        <strong>Lỗi!</strong> {{ session('error') }}
    </div>
@endif
<!-- =========================
     Form lọc sản phẩm
============================ -->
<div class="body-content">
         <form action="{{ route('admin.products.index') }}" method="GET" class="filter-form" style="margin-bottom: 20px;" id="filterForm">
         <div>
             <div>
                 <label for="keyword">Tìm kiếm:</label>
                 <input type="text" name="keyword" id="keyword" value="{{ request('keyword') }}" placeholder="Tên, mô tả, SKU, thương hiệu...">
             </div>
             <div>
                 <label for="brand">Thương hiệu:</label>
                 <input type="text" name="brand" id="brand" value="{{ request('brand') }}" placeholder="Nhập tên thương hiệu">
             </div>
             <div>
                 <label for="price_min">Giá từ:</label>
                 <input type="number" name="price_min" id="price_min" value="{{ request('price_min') }}" min="0" placeholder="0">
             </div>
             <div>
                 <label for="price_max">Đến:</label>
                 <input type="number" name="price_max" id="price_max" value="{{ request('price_max') }}" min="0" placeholder="999999999">
             </div>
             <div>
                 <label for="category">Danh mục:</label>
                 <select name="category" id="category">
                     <option value="">Tất cả danh mục</option>
                     @foreach ($categories as $cat)
                         <option value="{{ $cat->Categories_ID }}" {{ request('category') == $cat->Categories_ID ? 'selected' : '' }}>
                             {{ $cat->Name }}
                         </option>
                     @endforeach
                 </select>
             </div>
             <div>
                 <label for="status">Trạng thái:</label>
                 <select name="status" id="status">
                     <option value="">Tất cả</option>
                     <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Hiển thị</option>
                     <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Ẩn</option>
                 </select>
             </div>
         </div>

         <!-- Hàng thứ 2 của bộ lọc -->
         <div style="margin-top: 15px;">
             <div>
                 <label for="quantity_min">Số lượng từ:</label>
                 <input type="number" name="quantity_min" id="quantity_min" value="{{ request('quantity_min') }}" min="0" placeholder="0">
             </div>
             <div>
                 <label for="quantity_max">Đến:</label>
                 <input type="number" name="quantity_max" id="quantity_max" value="{{ request('quantity_max') }}" min="0" placeholder="999999">
             </div>
             <div>
                 <label for="has_discount">Khuyến mãi:</label>
                 <select name="has_discount" id="has_discount">
                     <option value="">Tất cả</option>
                     <option value="1" {{ request('has_discount') === '1' ? 'selected' : '' }}>Có khuyến mãi</option>
                     <option value="0" {{ request('has_discount') === '0' ? 'selected' : '' }}>Không khuyến mãi</option>
                 </select>
             </div>
             <div>
                 <label for="has_variants">Biến thể:</label>
                 <select name="has_variants" id="has_variants">
                     <option value="">Tất cả</option>
                     <option value="1" {{ request('has_variants') === '1' ? 'selected' : '' }}>Có biến thể</option>
                     <option value="0" {{ request('has_variants') === '0' ? 'selected' : '' }}>Không có biến thể</option>
                 </select>
             </div>
             <div>
                 <label for="is_featured">Nổi bật:</label>
                 <select name="is_featured" id="is_featured">
                     <option value="">Tất cả</option>
                     <option value="1" {{ request('is_featured') === '1' ? 'selected' : '' }}>Sản phẩm nổi bật</option>
                     <option value="0" {{ request('is_featured') === '0' ? 'selected' : '' }}>Không nổi bật</option>
                 </select>
             </div>
             <div>
                 <label for="is_hot">HOT:</label>
                 <select name="is_hot" id="is_hot">
                     <option value="">Tất cả</option>
                     <option value="1" {{ request('is_hot') === '1' ? 'selected' : '' }}>Sản phẩm HOT</option>
                     <option value="0" {{ request('is_hot') === '0' ? 'selected' : '' }}>Không HOT</option>
                 </select>
             </div>
         </div>

         <!-- Hàng thứ 3 - Sắp xếp -->
         <div style="margin-top: 15px;">
             <div>
                 <label for="sort_by">Sắp xếp theo:</label>
                 <select name="sort_by" id="sort_by">
                    <option value="Updated_at" {{ request('sort_by') === 'Updated_at' ? 'selected' : '' }}>Ngày cập nhật</option>
                     <option value="Product_ID" {{ request('sort_by') === 'Product_ID' ? 'selected' : '' }}>Mã sản phẩm</option>
                     <option value="Name" {{ request('sort_by') === 'Name' ? 'selected' : '' }}>Tên sản phẩm</option>
                     <option value="Price" {{ request('sort_by') === 'Price' ? 'selected' : '' }}>Giá</option>
                     <option value="Discount_price" {{ request('sort_by') === 'Discount_price' ? 'selected' : '' }}>Giá khuyến mãi</option>
                     <option value="Quantity" {{ request('sort_by') === 'Quantity' ? 'selected' : '' }}>Số lượng</option>
                     <option value="Brand" {{ request('sort_by') === 'Brand' ? 'selected' : '' }}>Thương hiệu</option>
                     <option value="Created_at" {{ request('sort_by') === 'Created_at' ? 'selected' : '' }}>Ngày tạo</option>
                 </select>
             </div>
             <div>
                 <label for="sort_order">Thứ tự:</label>
                 <select name="sort_order" id="sort_order">
                     <option value="desc" {{ request('sort_order') === 'desc' ? 'selected' : '' }}>Giảm dần</option>
                     <option value="asc" {{ request('sort_order') === 'asc' ? 'selected' : '' }}>Tăng dần</option>
                 </select>
             </div>
             <div style="display: flex; align-items: end; gap: 10px;">
                 <button type="submit" class="admin-form-loc">Lọc</button>
                 <a href="{{ route('admin.products.index') }}" class="admin-form-loc">Đặt lại</a>
             </div>
         </div>
          </form>

     <!-- Thông tin kết quả tìm kiếm -->
     @if(request()->hasAny(['keyword', 'brand', 'price_min', 'price_max', 'category', 'status', 'quantity_min', 'quantity_max', 'has_discount', 'has_variants', 'is_featured', 'is_hot']))
         <div class="search-results-info">
             <strong>Kết quả tìm kiếm:</strong> Tìm thấy {{ $products->total() }} sản phẩm
             @if($products->total() > 0)
                 (trang {{ $products->currentPage() }} / {{ $products->lastPage() }})
             @endif

             <!-- Hiển thị các điều kiện đã áp dụng -->
             <div style="margin-top: 8px; font-size: 12px; color: #666;">
                 <strong>Điều kiện đã áp dụng:</strong>
                 @php
                     $appliedFilters = [];
                     if(request('keyword')) $appliedFilters[] = 'Từ khóa: "' . request('keyword') . '"';
                     if(request('brand')) $appliedFilters[] = 'Thương hiệu: "' . request('brand') . '"';
                     if(request('category')) {
                         $cat = $categories->firstWhere('Categories_ID', request('category'));
                         $appliedFilters[] = 'Danh mục: "' . ($cat ? $cat->Name : 'N/A') . '"';
                     }
                     if(request('status') !== '' && request('status') !== null) {
                         $appliedFilters[] = 'Trạng thái: ' . (request('status') == '1' ? 'Hiển thị' : 'Ẩn');
                     }
                     if(request('price_min') || request('price_max')) {
                         $priceFilter = 'Giá: ';
                         if(request('price_min') && request('price_max')) {
                             $priceFilter .= number_format(request('price_min'), 0, ',', '.') . ' - ' . number_format(request('price_max'), 0, ',', '.') . '₫';
                         } elseif(request('price_min')) {
                             $priceFilter .= 'Từ ' . number_format(request('price_min'), 0, ',', '.') . '₫';
                         } else {
                             $priceFilter .= 'Đến ' . number_format(request('price_max'), 0, ',', '.') . '₫';
                         }
                         $appliedFilters[] = $priceFilter;
                     }
                     if(request('quantity_min') || request('quantity_max')) {
                         $qtyFilter = 'Số lượng: ';
                         if(request('quantity_min') && request('quantity_max')) {
                             $qtyFilter .= request('quantity_min') . ' - ' . request('quantity_max');
                         } elseif(request('quantity_min')) {
                             $qtyFilter .= 'Từ ' . request('quantity_min');
                         } else {
                             $qtyFilter .= 'Đến ' . request('quantity_max');
                         }
                         $appliedFilters[] = $qtyFilter;
                     }
                     if(request('has_discount') !== '' && request('has_discount') !== null) {
                         $appliedFilters[] = 'Khuyến mãi: ' . (request('has_discount') == '1' ? 'Có' : 'Không');
                     }
                     if(request('has_variants') !== '' && request('has_variants') !== null) {
                         $appliedFilters[] = 'Biến thể: ' . (request('has_variants') == '1' ? 'Có' : 'Không');
                     }
                     if(request('is_featured') !== '' && request('is_featured') !== null) {
                         $appliedFilters[] = 'Nổi bật: ' . (request('is_featured') == '1' ? 'Có' : 'Không');
                     }
                     if(request('is_hot') !== '' && request('is_hot') !== null) {
                         $appliedFilters[] = 'HOT: ' . (request('is_hot') == '1' ? 'Có' : 'Không');
                     }
                 @endphp

                 @if(count($appliedFilters) > 0)
                     {{ implode(' | ', $appliedFilters) }}
                 @else
                     Không có điều kiện nào được áp dụng
                 @endif
             </div>
         </div>
     @endif

     <!-- =========================
          Bảng danh sách sản phẩm
     ============================ -->
    <table class="table-product-list" width="100%" border="0" cellspacing="0">
        <thead>
            <tr>
                <th>STT</th>
                <th>Ảnh</th>
                <th>Tên sản phẩm</th>
                <th>Giá</th>
                <th>Giá KM</th>
                <th>Số lượng</th>
                <th>Thương hiệu</th>
                <th>Phân loại sản phẩm</th>
                <th>Mô tả</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
        @foreach($products as $key => $product)
            <tr>
                <td>{{ $key + 1 + ($products->currentPage() - 1) * $products->perPage() }}</td>
                <td>
                    @if ($product->Image)
                        <img src="{{ asset($product->Image) }}" width="60" height="60" alt="Hình sản phẩm">
                    @else
                        <span>Không có ảnh</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('admin.products.show', $product->Product_ID) }}" style="color:#0154b9; font-weight:700; text-decoration:none;">
                        {{ $product->Name }}
                    </a>
                    @if($product->is_featured)
                        <span style="color:#eab308;font-size:13px;">★ Nổi bật</span>
                    @endif
                    @if($product->is_best_seller)
                        <span style="color:#22c55e;font-size:13px;">★ Bán chạy</span>
                    @endif
                    @if($product->is_hot)
                        <span style="color:#ef4444;font-size:13px;">★ HOT</span>
                    @endif
                </td>
                <td>{{ number_format($product->Price, 0, ',', '.') }}₫</td>
                <td>{{ number_format($product->Discount_price, 0, ',', '.') }}₫</td>
                {{-- <td>{{ $product->total_variant_quantity }}</td> --}}
                <td>
                    {{-- Nếu có biến thể thì lấy tổng số lượng của biến thể, nếu không thì lấy Quantity gốc --}}
                    @if($product->variants()->exists())
                        {{ $product->variants->sum('Quantity') }}
                    @else
                        {{ $product->Quantity }}
                    @endif
                </td>
                <td>{{ $product->Brand }}</td>
                <!-- Phân loại sản phẩm -->
                <td style="position: relative;">
                    @if($product->variants && count($product->variants))
                        Có {{ count($product->variants) }} lựa chọn
                        <a href="#"
                           class="variant-detail-link"
                           data-product-id="{{ $product->Product_ID }}"
                           style="margin-left:8px;font-size:13px;color:#2563eb;text-decoration:underline; display:inline-block; padding:2px 4px; border:1px solid transparent;">
                            Xem chi tiết
                        </a>
                        <div class="variant-popup"
                             style="display:none; position:absolute; left:0; top:28px; z-index:1000; background:#fff; border:1px solid #1976d2; border-radius:10px; box-shadow:0 8px 32px rgba(37,99,235,0.15); min-width:380px; max-width:500px; padding:18px 22px; font-size:15px; max-height:400px; overflow:hidden;">
                            <div style="font-weight:600; color:#1976d2; margin-bottom:12px; display:flex; justify-content:space-between; align-items:center;">
                                <span>Danh sách biến thể:</span>
                                <button onclick="this.parentElement.parentElement.style.display='none'"
                                        style="background:none; border:none; color:#666; cursor:pointer; font-size:18px; padding:0; width:20px; height:20px; display:flex; align-items:center; justify-content:center;">×</button>
                            </div>
                            <ul style="margin:0; padding:0; max-height:260px; overflow-y:auto;">
                                @foreach($product->variants as $variant)
                                    <li style="margin-bottom:12px; color:#222; border-bottom:1px solid #f0f0f0; padding:12px 16px; background:#fafafa; border-radius:6px;">
                                        <div style="font-weight:600; margin-bottom:8px; color:#1e40af;">
                                            {{ $variant->Variant_name }}
                                        </div>
                                        <div style="font-size:13px; color:#4b5563;">
                                            <div style="display:flex; justify-content:space-between; margin-bottom:4px; align-items:center;">
                                                <span style="font-weight:500;">Giá gốc:</span>
                                                <span style="font-weight:600; color:#dc2626;">{{ number_format($variant->Price, 0, ',', '.') }}₫</span>
                                            </div>
                                            @if($variant->Discount_price && $variant->Discount_price > 0)
                                                <div style="display:flex; justify-content:space-between; margin-bottom:4px; align-items:center;">
                                                    <span style="font-weight:500; color:#059669;">Giá khuyến mãi:</span>
                                                    <span style="font-weight:700; color:#059669;">{{ number_format($variant->Discount_price, 0, ',', '.') }}₫</span>
                                                </div>
                                                @php
                                                    $discountPercent = round((($variant->Price - $variant->Discount_price) / $variant->Price) * 100);
                                                @endphp
                                                <div style="display:flex; justify-content:space-between; margin-bottom:4px; align-items:center;">
                                                    <span style="font-weight:500;">Giảm giá:</span>
                                                    <span style="font-weight:600; color:#ea580c;">-{{ $discountPercent }}%</span>
                                                </div>
                                            @endif
                                            <div style="display:flex; justify-content:space-between; align-items:center;">
                                                <span style="font-weight:500;">Tồn kho:</span>
                                                <span style="font-weight:600; color:{{ $variant->Quantity > 0 ? '#2563eb' : '#dc2626' }};">
                                                    {{ $variant->Quantity }} sản phẩm
                                                    @if($variant->Quantity == 0)
                                                        <span style="color:#dc2626; font-size:11px; margin-left:4px;">(Hết hàng)</span>
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        Không có lựa chọn
                    @endif
                </td>
                <td>{{ Str::limit($product->Description, 40) }}</td>
                <td>
                    @if($product->Status)
                        <span style="color:#22c55e;font-weight:500;">Hiển thị</span>
                    @else
                        <span style="color:#ef4444;font-weight:500;">Ẩn</span>
                    @endif
                </td>
                <td class="action-buttons">
                    <button class="admin-button-table">
                        <a href="{{ route('admin.products.edit', $product->Product_ID) }}" style="display:block; width:100%; height:100%; color:inherit; text-decoration:none;">Sửa</a>
                    </button>
                    <form action="{{ route('admin.products.destroy', $product->Product_ID) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="admin-button-table btn-delete" onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?')">Xóa</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <!-- =========================
         Phân trang sản phẩm
    ============================ -->
    {{ $products->links() }}
</div>

 <script>
 function toggleVariantBox(link) {
     console.log('toggleVariantBox called'); // Debug log

     // Ẩn tất cả popup khác
     var allPopups = document.querySelectorAll('.variant-popup');
     allPopups.forEach(function(popup) {
         popup.style.display = 'none';
     });

     // Hiện popup của dòng này
     var popup = link.nextElementSibling;
     if (popup && popup.classList.contains('variant-popup')) {
         var isVisible = popup.style.display === 'block';
         popup.style.display = isVisible ? 'none' : 'block';
         console.log('Popup display set to:', popup.style.display); // Debug log
     } else {
         console.log('Popup not found or not correct element'); // Debug log
     }
 }

 // Thêm event listener để đóng popup khi click ra ngoài
 document.addEventListener('click', function(e) {
     if (!e.target.closest('.variant-popup') && !e.target.closest('.variant-detail-link')) {
         var allPopups = document.querySelectorAll('.variant-popup');
         allPopups.forEach(function(popup) {
             popup.style.display = 'none';
         });
     }
 });

     // Cải thiện trải nghiệm form lọc
     document.addEventListener('DOMContentLoaded', function() {
         console.log('Filter form loaded'); // Debug log

         // Tắt auto-submit để người dùng có thể điền nhiều điều kiện trước khi lọc
         // Chỉ auto-submit cho sắp xếp để trải nghiệm tốt hơn
         const sortSelects = ['sort_by', 'sort_order'];
         sortSelects.forEach(function(selectId) {
             const select = document.getElementById(selectId);
             if (select) {
                 select.addEventListener('change', function() {
                     console.log('Sort changed, submitting form'); // Debug log
                     this.form.submit();
                 });
             }
         });

         // Thêm tính năng Enter để submit form
         const keywordInput = document.getElementById('keyword');
         if (keywordInput) {
             keywordInput.addEventListener('keypress', function(e) {
                 if (e.key === 'Enter') {
                     e.preventDefault();
                     console.log('Enter pressed in keyword, submitting form'); // Debug log
                     this.form.submit();
                 }
             });
         }

         // Thêm tính năng Enter cho input thương hiệu
         const brandInput = document.getElementById('brand');
         if (brandInput) {
             brandInput.addEventListener('keypress', function(e) {
                 if (e.key === 'Enter') {
                     e.preventDefault();
                     console.log('Enter pressed in brand, submitting form'); // Debug log
                     this.form.submit();
                 }
             });
         }

         // Thêm debug cho form submit
         const filterForm = document.querySelector('.filter-form');
         if (filterForm) {
             filterForm.addEventListener('submit', function(e) {
                 console.log('Form submitted with data:', new FormData(this)); // Debug log
             });
         }
     });

     // Validate giá min/max
     const priceMin = document.getElementById('price_min');
     const priceMax = document.getElementById('price_max');

     if (priceMin && priceMax) {
         priceMin.addEventListener('input', function() {
             if (priceMax.value && parseInt(this.value) > parseInt(priceMax.value)) {
                 this.setCustomValidity('Giá tối thiểu không được lớn hơn giá tối đa');
             } else {
                 this.setCustomValidity('');
             }
         });

         priceMax.addEventListener('input', function() {
             if (priceMin.value && parseInt(this.value) < parseInt(priceMin.value)) {
                 this.setCustomValidity('Giá tối đa không được nhỏ hơn giá tối thiểu');
             } else {
                 this.setCustomValidity('');
             }
         });
     }

     // Validate số lượng min/max
     const quantityMin = document.getElementById('quantity_min');
     const quantityMax = document.getElementById('quantity_max');

     if (quantityMin && quantityMax) {
         quantityMin.addEventListener('input', function() {
             if (quantityMax.value && parseInt(this.value) > parseInt(quantityMax.value)) {
                 this.setCustomValidity('Số lượng tối thiểu không được lớn hơn số lượng tối đa');
             } else {
                 this.setCustomValidity('');
             }
         });

         quantityMax.addEventListener('input', function() {
             if (quantityMin.value && parseInt(this.value) < parseInt(quantityMin.value)) {
                 this.setCustomValidity('Số lượng tối đa không được nhỏ hơn số lượng tối thiểu');
             } else {
                 this.setCustomValidity('');
             }
         });
     }

     // Highlight các trường đã được lọc
     const form = document.querySelector('.filter-form');
     if (form) {
         const inputs = form.querySelectorAll('input, select');
         inputs.forEach(function(input) {
             if (input.value && input.value !== '') {
                 input.style.backgroundColor = '#f0f9ff';
                 input.style.borderColor = '#3b82f6';
             }
         });
     }
 });
 </script>

<!-- Test script để đảm bảo JavaScript hoạt động -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Page loaded, checking for variant links...');

    // Kiểm tra xem có link nào không
    var links = document.querySelectorAll('.variant-detail-link');
    console.log('Found', links.length, 'variant detail links');

    // Thêm event listener cho mỗi link
    links.forEach(function(link, index) {
        console.log('Setting up link', index);
        link.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            // Ẩn tất cả popup khác
            var allPopups = document.querySelectorAll('.variant-popup');
            allPopups.forEach(function(popup) {
                popup.style.display = 'none';
            });

            // Hiện popup của dòng này
            var popup = this.nextElementSibling;
            if (popup && popup.classList.contains('variant-popup')) {
                var isVisible = popup.style.display === 'block';
                popup.style.display = isVisible ? 'none' : 'block';
                console.log('Popup display set to:', popup.style.display);
            } else {
                console.log('Popup not found or not correct element');
            }
        });
    });
});
</script>
@endsection
