@extends('layouts.layout')

@section('content')
<style>
    .variant-table {
        margin-top: 20px;
        background: #fff;
        border-collapse: collapse;
        width: 100%;
        box-shadow: 0 2px 8px #e0e0e0;
    }
    .variant-table th, .variant-table td {
        padding: 10px 12px;
        border: 1px solid #e0e0e0;
        text-align: center;
        font-size: 15px;
    }
    .variant-table th {
        background: #f7f7f7;
        font-weight: 600;
        color: #0154b9;
    }
    .variant-table input[type="number"], .variant-table input[type="text"] {
        width: 90px;
        padding: 4px 6px;
        border: 1px solid #bdbdbd;
        border-radius: 4px;
        font-size: 14px;
        text-align: right;
    }
    .variant-table input[type="checkbox"] {
        width: 18px;
        height: 18px;
    }
    .bulk-inputs {
        display: flex;
        gap: 12px;
        align-items: center;
        margin: 12px 0 18px 0;
    }
    .bulk-inputs input[type="number"] {
        width: 110px;
        padding: 4px 6px;
        border: 1px solid #bdbdbd;
        border-radius: 4px;
        font-size: 14px;
    }
    .bulk-inputs button {
        background: #0154b9;
        color: #fff;
        border: none;
        border-radius: 6px;
        padding: 7px 18px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.18s;
    }
    .bulk-inputs button:hover {
        background: #d70018;
    }
    .form-group-attribute {
        margin-bottom: 14px;
    }
    .attribute-name {
        font-size: 15px;
        font-weight: 600;
        color: #0154b9;
        margin-bottom: 6px;
    }
    .checkbox-group label {
        margin-right: 18px;
        margin-bottom: 6px;
    }
    .product-flags {
        display: flex;
        gap: 32px;
        margin: 18px 0 24px 0;
        align-items: center;
        justify-content: flex-start;
    }
    .flag-block {
        display: flex;
        flex-direction: column;
        align-items: center;
        background: #f7faff;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        padding: 18px 26px 14px 26px;
        min-width: 160px;
        transition: border 0.18s, box-shadow 0.18s;
        box-shadow: 0 2px 8px #e0e0e0;
        cursor: pointer;
        position: relative;
    }
    .flag-block input[type="checkbox"] {
        width: 26px;
        height: 26px;
        accent-color: #0154b9;
        margin-bottom: 10px;
        cursor: pointer;
    }
    .flag-block .flag-title {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 4px;
        color: #0154b9;
        text-align: center;
        letter-spacing: 0.5px;
    }
    .flag-block .flag-desc {
        font-size: 14px;
        color: #888;
        text-align: center;
        margin-bottom: 0;
    }
    .flag-block.hot {
        border-color: #d70018;
    }
    .flag-block.hot .flag-title {
        color: #d70018;
    }
    .flag-block.featured {
        border-color: #ffb300;
    }
    .flag-block.featured .flag-title {
        color: #ffb300;
    }
    .flag-block.best {
        border-color: #0154b9;
    }
    .flag-block.best .flag-title {
        color: #0154b9;
    }
    .flag-block input[type="checkbox"]:checked + .flag-title {
        text-decoration: underline;
    }
    .flag-block:hover {
        border-color: #0154b9;
        box-shadow: 0 6px 24px #b3d2ff;
    }
    @media (max-width: 900px) {
        .variant-table th, .variant-table td {
            font-size: 13px;
            padding: 7px 5px;
        }
        .variant-table input[type="number"], .variant-table input[type="text"] {
            width: 70px;
        }
        .bulk-inputs input[type="number"] {
            width: 80px;
        }
        .product-flags {
            flex-direction: column;
            gap: 18px;
            align-items: stretch;
        }
        .flag-block {
            min-width: unset;
            width: 100%;
            padding: 12px 10px;
        }
    }
    .variant-table td:nth-child(3), .variant-table th:nth-child(3) {
        min-width: 220px;
        max-width: 340px;
        width: 30%;
    }
    .variant-table input[name*="[SKU]"] {
        width: 98%;
        min-width: 180px;
        font-size: 15px;
        padding: 6px 8px;
        background: #f7faff;
        border: 1.5px solid #bdbdbd;
        border-radius: 5px;
    }
    .vnd-format {
        margin-left: 12px;
        color: #d70018;
        font-weight: 600;
        font-size: 15px;
    }
</style>

<!-- =========================
     Tiêu đề trang
============================ -->
<div class="head-title">
    <div class="left">
        <h1>Thêm sản phẩm</h1>
        <ul class="breadcrumb">
            <li><a href="#">Sản phẩm</a></li>
            <li><i class='bx bx-chevron-right'></i></li>
            <li><a class="active" href="#">Thêm sản phẩm</a></li>
        </ul>
    </div>
    <a href="{{ route('admin.products.index') }}" class="btn-download">
        <span class="text">Quay lại</span>
    </a>
</div>

<!-- =========================
     Form thêm sản phẩm mới
============================ -->
<div class="form-add">
    <h2>Thêm Sản Phẩm Mới</h2>

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Thông tin tổng quát -->
        <div class="form-group">
            <label for="Name">Tên sản phẩm</label>
            <input type="text" id="Name" name="Name" required>
        </div>
        <div class="form-group">
            <label for="SKU">Mã SKU sản phẩm</label>
            <input type="text" id="SKU" name="SKU">
        </div>
        <div class="form-group">
            <label for="Price">Giá mặc định</label>
            <input type="number" id="Price" name="Price" required oninput="showVND(this)" inputmode="numeric" pattern="[0-9]*">
            <span class="vnd-format" id="PriceVND"></span>
        </div>
        <div class="form-group">
            <label for="Discount_price">Giá khuyến mãi mặc định</label>
            <input type="number" id="Discount_price" name="Discount_price" oninput="showVND(this)" inputmode="numeric" pattern="[0-9]*">
            <span class="vnd-format" id="DiscountPriceVND"></span>
        </div>
        <div class="form-group">
            <label for="Quantity">Số lượng mặc định</label>
            <input type="number" id="Quantity" name="Quantity" min="0" value="0" required>
        </div>
        <div class="form-group">
            <label for="brand_id">Thương hiệu</label>
            <select id="brand_id" name="brand_id" required>
                <option value="">-- Chọn thương hiệu --</option>
                @foreach($brands as $brand)
                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
    <label for="product_line_id">Dòng sản phẩm</label>
    <select id="product_line_id" name="product_line_id">
        <option value="">-- Không chọn dòng (tùy chọn) --</option>
        @foreach($productLines as $line)
            <option value="{{ $line->id }}" data-brand="{{ $line->brand_id }}">
                {{ $line->brand_name }} - {{ $line->name }}
            </option>
        @endforeach
    </select>
    <small style="color: #666; font-size: 13px; display: block; margin-top: 4px;">
        💡 Dòng sản phẩm sẽ được lọc theo thương hiệu đã chọn
    </small>
</div>
        
        <div class="form-group">
            <label for="Description">Mô tả</label>
            <textarea id="Description" name="Description" rows="4"></textarea>
        </div>
        <div class="form-group">
            <label for="details">Chi tiết sản phẩm</label>
            <textarea id="details" name="details" rows="6" class="form-control">{{ old('details') }}</textarea>
        </div>
        
        <div class="form-group">
            <label for="Image">Ảnh đại diện</label>
            <input type="file" id="Image" name="Image" accept="image/*">
        </div>
        <div class="form-group">
            <label for="Images">Ảnh phụ</label>
            <input type="file" id="Images" name="Images[]" multiple accept="image/*">
        </div>
        <div class="form-group">
            <label for="Categories_ID">Danh mục</label>
            <select name="Categories_ID" id="Categories_ID" required>
                @foreach($categories as $category)
                    <option value="{{ $category->Categories_ID }}">{{ $category->Name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="Status">Trạng thái</label>
            <select id="Status" name="Status" required>
                <option value="1">Hiển thị</option>
                <option value="0">Ẩn</option>
            </select>
        </div>
        <div class="form-group product-flags">
            <label class="flag-block featured">
                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                <span class="flag-title">Sản phẩm nổi bật</span>
                <span class="flag-desc">Hiển thị ở mục nổi bật trên trang chủ</span>
            </label>
            <label class="flag-block hot">
                <input type="checkbox" name="is_hot" value="1" {{ old('is_hot') ? 'checked' : '' }}>
                <span class="flag-title">Sản phẩm HOT</span>
                <span class="flag-desc">Đánh dấu sản phẩm đang được quan tâm</span>
            </label>
            <label class="flag-block best">
                <input type="checkbox" name="is_best_seller" value="1" {{ old('is_best_seller') ? 'checked' : '' }}>
                <span class="flag-title">Bán chạy nhất</span>
                <span class="flag-desc">Hiển thị ở mục bán chạy nhất</span>
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
                                           value="{{ $value->Value }}">
                                    {{ $value->Value }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        <!-- Bảng biến thể tự động sinh -->
        <div class="bulk-inputs">
            <input type="number" id="bulk-price" placeholder="Giá hàng loạt">
            <input type="number" id="bulk-discount" placeholder="Giá KM hàng loạt">
            <input type="number" id="bulk-qty" placeholder="Số lượng hàng loạt">
            <button type="button" onclick="bulkFill()">Áp dụng cho tất cả biến thể</button>
        </div>
        <table class="table table-bordered variant-table" id="variant-table" style="display:none;">
            <thead>
                <tr>
                    <th>Chọn</th>
                    <th>Biến thể</th>
                    <th>SKU</th>
                    <th>Giá</th>
                    <th>Giá KM</th>
                    <th>Số lượng</th>
                </tr>
            </thead>
            <tbody id="variant-table-body"></tbody>
        </table>

        <div class="form-actions">
            <button type="button" onclick="showConfirm()">Thêm sản phẩm</button>
        </div>
    </form>
</div>

<!-- Modal xác nhận -->
<div id="confirmModal" style="display:none;position:fixed;z-index:9999;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.25);align-items:center;justify-content:center;">
    <div style="background:#fff;padding:32px 36px 28px 36px;border-radius:14px;max-width:540px;width:98vw;box-shadow:0 4px 24px #bdbdbd;">
        <h3 style="color:#0154b9;font-size:22px;font-weight:700;margin-bottom:18px;text-align:center;">Xác nhận tạo sản phẩm</h3>
        <div id="confirmInfo" style="font-size:16px;margin-bottom:18px;max-height:380px;overflow:auto;"></div>
        <div style="display:flex;gap:18px;justify-content:flex-end;">
            <button type="button" onclick="closeConfirm()" style="padding:8px 22px;border-radius:7px;border:none;background:#e0e0e0;font-weight:600;font-size:15px;">Hủy</button>
            <button type="button" onclick="submitForm()" style="padding:8px 22px;border-radius:7px;border:none;background:#0154b9;color:#fff;font-weight:600;font-size:15px;">Xác nhận</button>
        </div>
    </div>
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
            document.getElementById('variant-table').style.display = 'none';
            document.getElementById('variant-table-body').innerHTML = '';
            return;
        }
        // Sinh tổ hợp biến thể
        const combos = cartesian(attributes.map(attr => attr.values));
        const skuRoot = document.getElementById('SKU').value;
        const tbody = document.getElementById('variant-table-body');
        tbody.innerHTML = '';
        combos.forEach((combo, idx) => {
            const variantName = combo.join(' - ');
            // Tự động sinh SKU cho từng biến thể
            const sku = skuRoot
                ? skuRoot + '-' + combo.map(v => v.replace(/\s/g,'').toUpperCase()).join('-')
                : combo.map(v => v.replace(/\s/g,'').toUpperCase()).join('-');
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
                        <input type="number" name="variants[${idx}][Price]" class="form-control variant-price" inputmode="numeric" pattern="[0-9]*">
                        <span class="vnd-format"></span>
                    </td>
                    <td>
                        <input type="number" name="variants[${idx}][Discount_price]" class="form-control variant-discount" inputmode="numeric" pattern="[0-9]*">
                        <span class="vnd-format"></span>
                    </td>
                    <td>
                        <input type="number" name="variants[${idx}][Quantity]" class="form-control variant-qty">
                    </td>
                </tr>
            `;
        });
        document.getElementById('variant-table').style.display = '';
    }

    // Lắng nghe tick thuộc tính để sinh biến thể và cập nhật SKU
    document.querySelectorAll('.variant-attr').forEach(cb => {
        cb.addEventListener('change', updateVariantTable);
    });
    // Lắng nghe thay đổi SKU gốc để cập nhật SKU biến thể
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

    // Hiển thị giá VND realtime cho các ô nhập giá mặc định
    window.showVND = function(input) {
        let value = input.value.replace(/\D/g, '');
        let formatted = value ? Number(value).toLocaleString('vi-VN') + ' ₫' : '';
        if (input.id === 'Price') {
            document.getElementById('PriceVND').textContent = formatted;
        } else if (input.id === 'Discount_price') {
            document.getElementById('DiscountPriceVND').textContent = formatted;
        }
    };

    // Hiển thị VND cho các ô nhập giá biến thể
    document.addEventListener('input', function(e) {
        // Chỉ cho nhập số vào các ô giá (chặn mọi ký tự không phải số)
        if (
            e.target.matches('#Price, #Discount_price, .variant-price, .variant-discount, #bulk-price, #bulk-discount')
        ) {
            e.target.value = e.target.value.replace(/[^0-9]/g, '');
        }
        // Hiển thị VND realtime cho biến thể
        if (e.target.classList.contains('variant-price') || e.target.classList.contains('variant-discount')) {
            let span = e.target.nextElementSibling;
            if (!span || !span.classList.contains('vnd-format')) {
                span = document.createElement('span');
                span.className = 'vnd-format';
                e.target.parentNode.appendChild(span);
            }
            let value = e.target.value.replace(/\D/g, '');
            span.textContent = value ? Number(value).toLocaleString('vi-VN') + ' ₫' : '';
        }
    });

    // Xác nhận trước khi tạo sản phẩm
    document.querySelector('.form-actions button[type="submit"]').addEventListener('click', function(e) {
        e.preventDefault();
        // Hiển thị thông tin xác nhận
        document.getElementById('confirmInfo').innerHTML = `
            Tên sản phẩm: <strong>${document.getElementById('Name').value}</strong><br>
            SKU: <strong>${document.getElementById('SKU').value}</strong><br>
            Giá: <strong>${document.getElementById('Price').value} ₫</strong><br>
            Giá KM: <strong>${document.getElementById('Discount_price').value} ₫</strong><br>
            Số lượng: <strong>${document.getElementById('Quantity').value}</strong><br>
            Thương hiệu: <strong>${document.getElementById('brand_id').options[document.getElementById('brand_id').selectedIndex].text}</strong><br>
            Danh mục: <strong>${document.getElementById('Categories_ID').options[document.getElementById('Categories_ID').selectedIndex].text}</strong><br>
        `;
        // Hiện modal xác nhận
        document.getElementById('confirmModal').style.display = 'flex';
    });

    // Đóng modal xác nhận
    window.closeConfirm = function() {
        document.getElementById('confirmModal').style.display = 'none';
    }

    // Gửi form sau khi xác nhận
    window.submitForm = function() {
        document.querySelector('.form-add form').submit();
    }

    // Khởi tạo bộ lọc dòng sản phẩm theo thương hiệu
    function initProductLineFilter() {
        const brandSelect = document.getElementById('brand_id');
        const productLineSelect = document.getElementById('product_line_id');
        
        if (!brandSelect || !productLineSelect) {
            console.log('❌ Brand hoặc Product Line select không tìm thấy!');
            return;
        }
        
        console.log('✅ Product Line filter initialized');
        
        // Lưu HTML gốc
        const originalHTML = productLineSelect.innerHTML;
        
        brandSelect.addEventListener('change', function() {
            const selectedBrandId = this.value;
            console.log('🏷️ Brand selected ID:', selectedBrandId);
            
            // Reset về HTML gốc
            productLineSelect.innerHTML = originalHTML;
            
            if (selectedBrandId) {
                // Ẩn các option không thuộc brand được chọn
                Array.from(productLineSelect.options).forEach(option => {
                    if (option.value !== '' && option.dataset.brand !== selectedBrandId) {
                        option.style.display = 'none';
                        option.disabled = true;
                    } else {
                        option.style.display = '';
                        option.disabled = false;
                    }
                });
            } else {
                // Hiện tất cả nếu không chọn brand
                Array.from(productLineSelect.options).forEach(option => {
                    option.style.display = '';
                    option.disabled = false;
                });
            }
        });
    }

    initProductLineFilter();
});


</script>

<script>
function showConfirm() {
    // Lấy thông tin sản phẩm gốc
    let name = document.getElementById('Name').value;
    let sku = document.getElementById('SKU').value;
    let price = document.getElementById('Price').value;
    let qty = document.getElementById('Quantity').value;
    let cat = document.getElementById('Categories_ID');
    let catText = cat.options[cat.selectedIndex].text;
    let brand = document.getElementById('brand_id');
    let brandText = brand.options[brand.selectedIndex].text;

    
    // THÊM PHẦN NÀY:
    let productLine = document.getElementById('product_line_id');
    let productLineText = 'Không chọn';
    if (productLine.value) {
        productLineText = productLine.options[productLine.selectedIndex].text;
    }

    // Lấy thông tin biến thể (nếu có)
    let variantsTable = document.getElementById('variant-table');
    let variantsHtml = '';
    if (variantsTable && variantsTable.style.display !== 'none') {
        let rows = document.querySelectorAll('#variant-table-body tr');
        variantsHtml = `<table style="width:100%;border-collapse:collapse;margin-top:10px;font-size:15px;">
            <tr style="background:#f7f7f7;">
                <th style="border:1px solid #e0e0e0;padding:6px 10px;">Biến thể</th>
                <th style="border:1px solid #e0e0e0;padding:6px 10px;">SKU</th>
                <th style="border:1px solid #e0e0e0;padding:6px 10px;">Số lượng</th>
            </tr>`;
        rows.forEach(row => {
            let variant = row.querySelector('input[name*="[Variant_name]"]').value;
            let sku = row.querySelector('input[name*="[SKU]"]').value;
            let qty = row.querySelector('input[name*="[Quantity]"]').value;
            variantsHtml += `<tr>
                <td style="border:1px solid #e0e0e0;padding:6px 10px;">${variant}</td>
                <td style="border:1px solid #e0e0e0;padding:6px 10px;color:#0154b9;font-weight:600;">${sku}</td>
                <td style="border:1px solid #e0e0e0;padding:6px 10px;text-align:center;">${qty}</td>
            </tr>`;
        });
        variantsHtml += `</table>`;
    }

    // Tìm phần hiển thị info và sửa:
let info = `
    <div style="margin-bottom:12px;">
        <div style="font-size:17px;font-weight:700;color:#d70018;margin-bottom:4px;">Sản phẩm gốc</div>
        <div><b>Tên:</b> ${name}</div>
        <div><b>SKU:</b> <span style="color:#0154b9;">${sku}</span></div>
        <div><b>Giá:</b> <span style="color:#d70018;font-weight:600;">${Number(price).toLocaleString('vi-VN')} ₫</span></div>
        <div><b>Số lượng:</b> <span style="color:#0154b9;font-weight:600;">${qty}</span></div>
        <div><b>Thương hiệu:</b> ${brandText}</div>
        <div><b>Dòng sản phẩm:</b> <span style="color:#0154b9;">${productLineText}</span></div>
        <div><b>Danh mục:</b> ${catText}</div>
    </div>
    ${variantsHtml ? '<div style="font-size:17px;font-weight:700;color:#0154b9;margin:12px 0 6px 0;">Các biến thể được chọn tạo</div>' + variantsHtml : ''}
`;
    document.getElementById('confirmInfo').innerHTML = info;
    document.getElementById('confirmModal').style.display = 'flex';
}
function closeConfirm() {
    document.getElementById('confirmModal').style.display = 'none';
}
function submitForm() {
    document.querySelector('.form-add form').submit();
}
</script>
<!-- CKEditor CDN -->
        <script src="https://cdn.ckeditor.com/ckeditor5/40.0.1/classic/ckeditor.js"></script>
        <script>
            ClassicEditor
                .create(document.querySelector('#details'))
                .catch(error => console.error(error));
        </script>
@endsection
