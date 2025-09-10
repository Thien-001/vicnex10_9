@extends('layouts.layout')

@section('title', 'Sửa nhận xét chuyên gia')

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
        <h1>Sửa nhận xét chuyên gia</h1>
        <ul class="breadcrumb">
            <li><a href="{{ route('admin.expert-reviews.index') }}">Nhận xét chuyên gia</a></li>
            <li><i class='bx bx-chevron-right'></i></li>
            <li><a class="active" href="#">Sửa nhận xét</a></li>
        </ul>
    </div>
</div>

<div class="form-expert-review mt-4">
    <form action="{{ route('admin.expert-reviews.update', $review) }}" method="POST">
        @csrf
        @method('PUT')
        <label>Sản phẩm *</label>
        <select name="product_id" required>
            <option value="">-- Chọn sản phẩm --</option>
            @foreach($products as $product)
                <option value="{{ $product->id }}" {{ old('product_id', $review->product_id) == $product->id ? 'selected' : '' }}>
                    {{ $product->Name }}
                </option>
            @endforeach
        </select>

        <label>Chuyên gia *</label>
        <select name="expert_id" required>
            <option value="">-- Chọn chuyên gia --</option>
            @foreach($experts as $expert)
                <option value="{{ $expert->id }}" {{ old('expert_id', $review->expert_id) == $expert->id ? 'selected' : '' }}>
                    {{ $expert->name }}
                </option>
            @endforeach
        </select>

        <label>Nội dung nhận xét *</label>
        <textarea name="content" required>{{ old('content', $review->content) }}</textarea>

        <button type="submit">Cập nhật</button>
        <a href="{{ route('admin.expert-reviews.index') }}" class="btn-cancel btn">Quay lại</a>
    </form>
</div>
@endsection