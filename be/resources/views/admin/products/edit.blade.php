@extends('layouts.layout')

@section('content')
<style>
    .variant-table { margin-top: 20px; background: #fff; }
    .variant-table th, .variant-table td { padding: 8px 10px; }
    .product-attributes { margin-top: 20px; background: #fff; padding: 15px; }
    .form-group-attribute { display: flex; align-items: flex-start; margin-bottom: 10px; gap: 20px; flex-wrap: wrap; }
    .form-group-attribute .attribute-name { min-width: 120px; font-weight: 600; font-size: 15px; color: #444; }
    .checkbox-group { display: flex; flex-wrap: wrap; gap: 10px; }
    .checkbox-group label { display: flex; align-items: center; font-size: 14px; cursor: pointer; }
    .checkbox-group input[type="checkbox"] { margin-right: 6px; }
</style>

<!-- =========================
     Tiêu đề trang
============================ -->
<div class="head-title">
    <div class="left">
        <h1>Sửa sản phẩm</h1>
        <ul class="breadcrumb">
            <li><a href="#">Sản phẩm</a></li>
            <li><i class='bx bx-chevron-right'></i></li>
            <li><a class="active" href="#">Sửa sản phẩm</a></li>
        </ul>
    </div>
    <a href="{{ route('admin.products.index') }}" class="btn-download">
        <span class="text">Quay lại</span>
    </a>
</div>

<!-- =========================
     Form cập nhật sản phẩm
============================ -->
<div class="form-add">
    <h2>Sửa Sản Phẩm</h2>

    <form action="{{ route('admin.products.update', $product->Product_ID) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Thông tin tổng quát -->
        <div class="form-group">
            <label for="Name">Tên sản phẩm</label>
            <input type="text" id="Name" name="Name" value="{{ $product->Name }}" required>
        </div>
        <div class="form-group">
            <label for="SKU">Mã SKU sản phẩm</label>
            <input type="text" id="SKU" name="SKU" value="{{ $product->SKU }}">
        </div>
        <div class="form-group">
            <label for="Price">Giá mặc định</label>
            <input type="number" id="Price" name="Price" value="{{ $product->Price }}" required>
        </div>
        <div class="form-group">
            <label for="Discount_price">Giá khuyến mãi mặc định</label>
            <input type="number" id="Discount_price" name="Discount_price" value="{{ $product->Discount_price }}">
        </div>
        <div class="form-group">
            <label for="Quantity">Số lượng</label>
            @if($product->variants()->exists())
                <input type="number" class="form-control" value="{{ $product->variants->sum('Quantity') }}" disabled>
                {{-- Hidden field luôn gửi về server --}}
                <input type="hidden" name="Quantity" value="{{ $product->variants->sum('Quantity') }}">
                <small class="text-muted">Số lượng này được tính tự động theo biến thể</small>
            @else
                <input type="number" name="Quantity" class="form-control" value="{{ old('Quantity', $product->Quantity) }}">
            @endif
        </div>

        <div class="form-group">
            <label for="brand_id">Thương hiệu</label>
            <select id="brand_id" name="brand_id" required>
                <option value="">-- Chọn thương hiệu --</option>
                @foreach($brands as $brand)
                    <option value="{{ $brand->id }}" {{ $product->brand_id == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="Description">Mô tả</label>
            <textarea id="Description" name="Description" rows="4">{{ $product->Description }}</textarea>
        </div>
        <div class="form-group">
            <label for="details">Chi tiết sản phẩm</label>
            <textarea id="details" name="details" rows="6" class="form-control">{{ $product->details }}</textarea>
        </div>
        <script src="https://cdn.ckeditor.com/ckeditor5/40.0.1/classic/ckeditor.js"></script>
        <script>
            ClassicEditor
                .create(document.querySelector('#details'))
                .catch(error => console.error(error));
        </script>
        <div class="form-group">
            <label for="Image">Ảnh đại diện</label>
            <input type="file" id="Image" name="Image" accept="image/*">
            @if($product->Image)
                <div><img src="{{ asset($product->Image) }}" width="80"></div>
            @endif
        </div>
        <div class="form-group">
            <label for="Images">Ảnh phụ</label>
            <input type="file" id="Images" name="Images[]" multiple accept="image/*">
            @if($product->images)
                <div style="display:flex;gap:8px;margin-top:5px;">
                    @foreach($product->images as $img)
                        <img src="{{ asset($img->Image_path) }}" width="60">
                    @endforeach
                </div>
            @endif
        </div>
        <div class="form-group">
            <label for="Categories_ID">Danh mục</label>
            <select name="Categories_ID" id="Categories_ID" required>
                @foreach($categories as $category)
                    <option value="{{ $category->Categories_ID }}" {{ $product->Categories_ID == $category->Categories_ID ? 'selected' : '' }}>{{ $category->Name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="Status">Trạng thái</label>
            <select id="Status" name="Status" required>
                <option value="1" {{ $product->Status ? 'selected' : '' }}>Hiển thị</option>
                <option value="0" {{ !$product->Status ? 'selected' : '' }}>Ẩn</option>
            </select>
        </div>
        <div class="form-group">
            <label>
                <input type="checkbox" name="is_featured" value="1" {{ $product->is_featured ? 'checked' : '' }}> Sản phẩm nổi bật
            </label>
            <label style="margin-left:20px;">
                <input type="checkbox" name="is_hot" value="1" {{ $product->is_hot ? 'checked' : '' }}> Sản phẩm HOT
            </label>
            <label style="margin-left:20px;">
                <input type="checkbox" name="is_best_seller" value="1" {{ $product->is_best_seller ? 'checked' : '' }}> Bán chạy nhất
            </label>
        </div>

        <!-- Chọn thuộc tính cho biến thể (chỉ hiện thuộc tính theo danh mục) -->
        <div class="product-attributes">
            <h4>Chọn thuộc tính cho biến thể</h4>
            @foreach ($attributes as $attribute)
                @if (!($attribute->Categories_ID == 1 && $attribute->Name == 'Kiểu dáng'))
                    <div class="form-group-attribute attribute-cat-{{ $attribute->Categories_ID }}" data-attribute="{{ $attribute->Name }}" style="display:none;">
                        <div class="attribute-name">{{ $attribute->Name }}</div>
                        <div class="checkbox-group">
                            @foreach ($attribute->values as $value)
                                <label>
                                    <input type="checkbox"
                                           class="variant-attr"
                                           data-attr="{{ $attribute->Name }}"
                                           value="{{ $value->Value }}"
                                           {{ in_array($value->Value, $selectedValues[$attribute->Name] ?? []) ? 'checked' : '' }}>
                                    {{ $value->Value }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        <!-- Bảng biến thể -->
        <div style="margin:10px 0;">
            <input type="number" id="bulk-price" placeholder="Giá hàng loạt">
            <input type="number" id="bulk-discount" placeholder="Giá KM hàng loạt">
            <input type="number" id="bulk-qty" placeholder="Số lượng hàng loạt">
            <button type="button" onclick="bulkFill()">Áp dụng cho tất cả biến thể</button>
        </div>
        <table class="table table-bordered variant-table" id="variant-table" style="{{ count($product->variants) ? '' : 'display:none;' }}">
            <thead>
                <tr>
                    <th>Tạo?</th>
                    <th>Biến thể</th>
                    <th>SKU</th>
                    <th>Giá</th>
                    <th>Giá KM</th>
                    <th>Số lượng</th>
                    <th>Ảnh</th>
                    <th>Xóa</th>
                </tr>
            </thead>
            <tbody id="variant-table-body">
                @foreach($product->variants as $i => $variant)
                    <tr>
                        <td>
                            <input type="checkbox" name="variants[{{ $i }}][enabled]" value="1" checked>
                            <input type="hidden" name="variants[{{ $i }}][Variant_ID]" value="{{ $variant->Variant_ID }}">
                        </td>
                        <td>
                            <input type="text" name="variants[{{ $i }}][Variant_name]" value="{{ $variant->Variant_name }}" class="form-control">
                        </td>
                        <td>
                            <input type="text" name="variants[{{ $i }}][SKU]" value="{{ $variant->SKU }}" class="form-control">
                        </td>
                        <td>
                            <input type="number" name="variants[{{ $i }}][Price]" value="{{ $variant->Price }}" class="form-control">
                        </td>
                        <td>
                            <input type="number" name="variants[{{ $i }}][Discount_price]" value="{{ $variant->Discount_price }}" class="form-control">
                        </td>
                        <td>
                            <input type="number" name="variants[{{ $i }}][Quantity]" value="{{ $variant->Quantity }}" class="form-control">
                        </td>
                        <td>
                            <input type="file" name="variants[{{ $i }}][Image]" accept="image/*">
                            @if($variant->Image)
                                <div><img src="{{ asset($variant->Image) }}" width="60"></div>
                            @endif
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger" onclick="this.closest('tr').remove();">Xóa</button>
                        </td>
                    </tr>x`
                @endforeach
            </tbody>
        </table>

        <div class="form-actions">
            <button type="submit">Cập nhật sản phẩm</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Hiện thuộc tính theo danh mục
    function showAttributesByCategory(catId) {
        document.querySelectorAll('.form-group-attribute').forEach(function(el) {
            el.style.display = 'none';
        });
        document.querySelectorAll('.attribute-cat-' + catId).forEach(function(el) {
            el.style.display = '';
        });
        // Sau khi ẩn/hiện thuộc tính, cập nhật lại bảng biến thể
        updateVariantTable();
    }
    var select = document.getElementById('Categories_ID');
    if (select) {
        showAttributesByCategory(select.value);
        select.addEventListener('change', function() {
            showAttributesByCategory(this.value);
        });
    }

    // --- Sinh biến thể tự động ---
    function getCheckedValues(attrName) {
        return Array.from(document.querySelectorAll('.variant-attr[data-attr="'+attrName+'"]:checked')).map(cb => cb.value);
    }

    function getAllAttributes() {
        return Array.from(document.querySelectorAll('.form-group-attribute')).map(group => {
            return {
                name: group.getAttribute('data-attribute'),
                values: getCheckedValues(group.getAttribute('data-attribute'))
            };
        });
    }

    function cartesian(arr) {
        return arr.reduce(function(a, b) {
            var ret = [];
            a.forEach(function(aItem) {
                b.forEach(function(bItem) {
                    ret.push(aItem.concat([bItem]));
                });
            });
            return ret;
        }, [[]]);
    }

    function updateVariantTable() {
        const attributes = getAllAttributes().filter(attr => attr.values.length > 0 && document.querySelector('.attribute-cat-' + select.value + '[data-attribute="' + attr.name + '"]').style.display !== 'none');
        if (attributes.length === 0) {
            // Nếu đã có biến thể cũ (render từ blade), vẫn giữ nguyên bảng
            return;
        }
        // Sinh tổ hợp biến thể
        const combos = cartesian(attributes.map(attr => attr.values));
        const tbody = document.getElementById('variant-table-body');
        tbody.innerHTML = '';
        combos.forEach((combo, idx) => {
            const variantName = combo.join(' - ');
            const sku = document.getElementById('SKU').value ? document.getElementById('SKU').value + '-' + combo.map(v => v.replace(/\s/g,'').toUpperCase()).join('-') : '';
            tbody.innerHTML += `
                <tr>
                    <td>
                        <input type="checkbox" name="variants[${idx}][enabled]" value="1" checked>
                    </td>
                    <td>
                        <input type="hidden" name="variants[${idx}][Variant_name]" value="${variantName}">
                        ${variantName}
                    </td>
                    <td>
                        <input type="text" name="variants[${idx}][SKU]" value="${sku}" class="form-control">
                    </td>
                    <td>
                        <input type="number" name="variants[${idx}][Price]" class="form-control variant-price">
                    </td>
                    <td>
                        <input type="number" name="variants[${idx}][Discount_price]" class="form-control variant-discount">
                    </td>
                    <td>
                        <input type="number" name="variants[${idx}][Quantity]" class="form-control variant-qty">
                    </td>
                    <td>
                        <input type="file" name="variants[${idx}][Image]" accept="image/*">
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger" onclick="this.closest('tr').remove();">Xóa</button>
                    </td>
                </tr>
            `;
        });
        document.getElementById('variant-table').style.display = '';
    }

    // Lắng nghe tick thuộc tính để sinh biến thể
    document.querySelectorAll('.variant-attr').forEach(cb => {
        cb.addEventListener('change', updateVariantTable);
    });

    // Gợi ý SKU tự động khi thay đổi SKU gốc
    document.getElementById('SKU').addEventListener('input', updateVariantTable);

    // Cho phép nhập giá/số lượng hàng loạt
    window.bulkFill = function() {
        let price = document.getElementById('bulk-price').value;
        let discount = document.getElementById('bulk-discount').value;
        let qty = document.getElementById('bulk-qty').value;
        document.querySelectorAll('.variant-price').forEach(i => { if(price) i.value = price; });
        document.querySelectorAll('.variant-discount').forEach(i => { if(discount) i.value = discount; });
        document.querySelectorAll('.variant-qty').forEach(i => { if(qty) i.value = qty; });
    };
});
</script>
@endsection
