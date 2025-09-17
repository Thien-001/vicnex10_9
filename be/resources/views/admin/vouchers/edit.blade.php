@extends('layouts.layout')

@section('content')
<main>
    <div class="head-title">
        <div class="left">
            <h1>Chỉnh sửa Voucher</h1>
            <ul class="breadcrumb">
                <li><a href="#">Voucher</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Chỉnh sửa voucher</a></li>
            </ul>
        </div>
        <a href="{{ route('admin.vouchers.index') }}" class="btn-download">
            <span class="text">Quay lại</span>
        </a>
    </div>

    <div class="form-add">
        <h2>Cập nhật thông tin Voucher</h2>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.vouchers.update', $voucher->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="code">Mã Voucher <span style="color:red">*</span></label>
                <input type="text" id="code" name="code" value="{{ old('code', $voucher->code) }}" required>
            </div>

            <div class="form-group">
                <label for="discount_type">Loại giảm giá <span style="color:red">*</span></label>
                <select id="discount_type" name="discount_type" required>
                    <option value="percentage" {{ $voucher->discount_type == 'percentage' ? 'selected' : '' }}>Phần trăm (%)</option>
                    <option value="fixed" {{ $voucher->discount_type == 'fixed' ? 'selected' : '' }}>Cố định (VNĐ)</option>
                </select>
            </div>

            <div class="form-group">
                <label for="discount_value">Giá trị giảm <span style="color:red">*</span></label>
                <input type="number" step="0.01" id="discount_value" name="discount_value" value="{{ old('discount_value', $voucher->discount_value) }}" required>
            </div>

            <div class="form-group">
                <label for="max_uses">Lượt dùng tối đa</label>
                <input type="number" id="max_uses" name="max_uses" value="{{ old('max_uses', $voucher->max_uses) }}">
            </div>

            <div class="form-group">
                <label for="expires">Ngày hết hạn</label>
                <input type="date" id="expires" name="expires" value="{{ old('expires', $voucher->expires) }}">
            </div>

            <div class="form-group">
                <label>Áp dụng cho</label>
                <div>
                    <label>
                        <input type="radio" name="applies_to" value="all"
                            {{ (old('applies_to', $voucher->applies_to) == 'all') ? 'checked' : '' }}>
                        Tất cả
                    </label>
                    <label style="margin-left:20px;">
                        <input type="radio" name="applies_to" value="booking"
                            {{ (old('applies_to', $voucher->applies_to) == 'booking') ? 'checked' : '' }}>
                        Đặt sân
                    </label>
                </div>
                <div style="margin-top:10px;">
                    <label>Hoặc chọn danh mục áp dụng:</label>
                    <div class="category-checkbox-list">
                        @php
                            $appliedCategories = explode(',', old('applies_to', $voucher->applies_to));
                        @endphp
                        @foreach($categories as $cat)
                            <label>
                                <input type="checkbox" name="applies_to_categories[]" value="{{ $cat->Categories_ID }}"
                                    {{ in_array($cat->Categories_ID, $appliedCategories) ? 'checked' : '' }}>
                                {{ $cat->Name }}
                            </label>
                        @endforeach
                    </div>
                </div>
                <small style="color:#888;">
                    Nếu chọn "Tất cả" hoặc "Đặt sân" thì không cần chọn danh mục.
                </small>
            </div>

            <div class="form-group">
                <label for="paid_at">Ngày đã sử dụng hết</label>
                <input type="date" id="paid_at" name="paid_at" value="{{ old('paid_at', $voucher->paid_at) }}">
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Cập nhật Voucher</button>
            </div>
        </form>
    </div>
</main>
@endsection

<style>
.form-group .category-checkbox-list {
    display: flex;
    flex-wrap: wrap;
    gap: 12px 24px;
    margin-top: 8px;
    margin-bottom: 4px;
}
.form-group .category-checkbox-list label {
    display: flex;
    align-items: center;
    font-weight: 500;
    background: #f6f8fa;
    border-radius: 6px;
    padding: 4px 12px 4px 6px;
    cursor: pointer;
    transition: background 0.2s;
}
.form-group .category-checkbox-list label:hover {
    background: #e0e7ff;
}
.form-group .category-checkbox-list input[type="checkbox"] {
    margin-right: 6px;
    accent-color: #0154b9;
}
</style>
