@extends('layouts.layout')

@section('title', 'Thêm nhận xét chuyên gia')

@section('content')
<style>
.form-expert-review {
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 4px 24px rgba(1,84,185,0.08);
    padding: 32px 28px;
    max-width: 600px;
    margin: 0 auto;
}
.form-expert-review label {
    font-weight: 600;
    color: #0154b9;
}
.form-expert-review input, .form-expert-review select, .form-expert-review textarea {
    border-radius: 6px;
    border: 1.5px solid #e3e8f0;
    padding: 8px 12px;
    width: 100%;
    margin-bottom: 18px;
    font-size: 15px;
}
.form-expert-review textarea { min-height: 90px; }
.form-expert-review button {
    background: #0154b9;
    color: #fff;
    border: none;
    border-radius: 6px;
    padding: 8px 28px;
    font-weight: 600;
    font-size: 16px;
    margin-right: 10px;
    transition: background 0.18s;
}
.form-expert-review button:hover { background: #003c7e; }
.form-expert-review .btn-cancel {
    background: #f4f9fd;
    color: #0154b9;
    border: 1.5px solid #0154b9;
}
.form-expert-review .btn-cancel:hover {
    background: #0154b9;
    color: #fff;
}
</style>

<div class="head-title">
    <div class="left">
        <h1>Thêm nhận xét chuyên gia</h1>
        <ul class="breadcrumb">
            <li><a href="{{ route('admin.expert-reviews.index') }}">Nhận xét chuyên gia</a></li>
            <li><i class='bx bx-chevron-right'></i></li>
            <li><a class="active" href="#">Thêm nhận xét</a></li>
        </ul>
    </div>
</div>

<div class="form-expert-review mt-4">
    <form action="{{ route('admin.expert-reviews.store') }}" method="POST">
        @csrf

        {{-- Chọn danh mục --}}
        <label>Danh mục sản phẩm *</label>
        <select id="category-select" required>
            <option value="">-- Chọn danh mục --</option>
            @foreach($categories as $category)
                <option value="{{ $category->Categories_ID }}">{{ $category->Name }}</option>
            @endforeach
        </select>

        {{-- Chọn sản phẩm --}}
        <label>Sản phẩm *</label>
        <select name="product_id" id="product-select" required disabled>
            <option value="">-- Chọn sản phẩm --</option>
            {{-- Các option sẽ được JS fill vào --}}
        </select>

        <label>Chuyên gia *</label>
        <select name="expert_id" required>
            <option value="">-- Chọn chuyên gia --</option>
            @foreach($experts as $expert)
                <option value="{{ $expert->id }}" {{ old('expert_id') == $expert->id ? 'selected' : '' }}>
                    {{ $expert->name }}
                </option>
            @endforeach
        </select>

        <label>Nội dung nhận xét *</label>
        <textarea name="content" required>{{ old('content') }}</textarea>

        <button type="submit">Lưu</button>
        <a href="{{ route('admin.expert-reviews.index') }}" class="btn-cancel btn">Quay lại</a>
    </form>
</div>

<script>
const productsByCategory = @json($categories->mapWithKeys(function($cat) {
    return [$cat->Categories_ID => $cat->products->map(function($p) {
        return ['id' => $p->Product_ID, 'name' => $p->Name];
    })];
}));

document.addEventListener('DOMContentLoaded', function() {
    const categorySelect = document.getElementById('category-select');
    const productSelect = document.getElementById('product-select');

    categorySelect.addEventListener('change', function() {
        const selectedCategory = this.value;
        productSelect.innerHTML = '<option value="">-- Chọn sản phẩm --</option>';
        if(productsByCategory[selectedCategory] && productsByCategory[selectedCategory].length > 0) {
            productsByCategory[selectedCategory].forEach(function(product) {
                const option = document.createElement('option');
                option.value = product.id;
                option.textContent = product.name;
                productSelect.appendChild(option);
            });
            productSelect.removeAttribute('disabled');
        } else {
            productSelect.setAttribute('disabled', 'disabled');
        }
    });

    // Khi submit form, luôn enable select sản phẩm để gửi dữ liệu
    document.querySelector('form').addEventListener('submit', function() {
        productSelect.removeAttribute('disabled');
    });
});
</script>
@endsection