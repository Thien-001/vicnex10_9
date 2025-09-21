@extends('layouts.layout')

@section('content')
<style>
.flashsale-form-container {
    /* max-width: 600px; */
    margin: 32px auto;
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 4px 24px rgba(1,84,185,0.08);
    padding: 32px 28px;
}
.flashsale-form-container h2 {
    /* color: #0154b9; */
    font-weight: 700;
    margin-bottom: 28px;
    text-align: center;
}
.flashsale-form-container .form-label {
    font-weight: 500;
    /* color: #0154b9; */
}
.flashsale-form-container .form-control,
.flashsale-form-container .form-select {
    border-radius: 8px;
    border: 1px solid #e0e7ff;
    font-size: 16px;
    padding: 10px 12px;
    margin-bottom: 16px;
    background: #f6f8fc;
    transition: border 0.2s;
}
.flashsale-form-container .form-control:focus,
.flashsale-form-container .form-select:focus {
    /* border-color: #0154b9; */
    background: #fff;
}
.flashsale-form-container .btn-primary {
    background: linear-gradient(90deg,#0154b9 0%,#3bb2ff 100%);
    border: none;
    border-radius: 8px;
    font-weight: 700;
    padding: 12px 32px;
    font-size: 17px;
    margin-right: 12px;
    box-shadow: 0 2px 8px rgba(1,84,185,0.12);
    transition: background 0.2s;
}
.flashsale-form-container .btn-primary:hover {
    background: linear-gradient(90deg,#3bb2ff 0%,#0154b9 100%);
}
.flashsale-form-container .btn-secondary {
    width: 100%;
    background-color: #3498db;
    color: white;
    padding: 10px 20px;
    font-size: 15px;
    border: none;
    margin-top: 10px;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}
.flashsale-form-container .btn-secondary:hover {
    background: #4a65d1;
    /* color: #003c7a; */
}
</style>
<div class="flashsale-form-container">
    <h2>Sửa Flash Sale</h2>
    <form action="{{ route('admin.flash-sales.update', $flashSale->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="product_id" class="form-label">Sản phẩm</label>
            <select name="product_id" id="product_id" class="form-select" required>
                @foreach($products as $product)
                    <option value="{{ $product->Product_ID }}" {{ $flashSale->product_id == $product->Product_ID ? 'selected' : '' }}>
                        {{ $product->Name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="price_old" class="form-label">Giá gốc</label>
            <input type="number" name="price_old" id="price_old" class="form-control" value="{{ $flashSale->price_old }}" min="0">
        </div>
        <div class="mb-3">
            <label for="price_sale" class="form-label">Giá sale</label>
            <input type="number" name="price_sale" id="price_sale" class="form-control" value="{{ $flashSale->price_sale }}" min="0" required>
        </div>
        <div class="mb-3">
            <label for="discount" class="form-label">Giảm (%)</label>
            <input type="number" name="discount" id="discount" class="form-control" value="{{ $flashSale->discount }}" min="0" max="100">
        </div>
        <div class="mb-3">
            <label for="start_time" class="form-label">Thời gian bắt đầu</label>
            <input type="datetime-local" name="start_time" id="start_time" class="form-control" value="{{ \Carbon\Carbon::parse($flashSale->start_time)->format('Y-m-d\TH:i') }}">
        </div>
        <div class="mb-3">
            <label for="end_time" class="form-label">Thời gian kết thúc</label>
            <input type="datetime-local" name="end_time" id="end_time" class="form-control" value="{{ \Carbon\Carbon::parse($flashSale->end_time)->format('Y-m-d\TH:i') }}">
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Trạng thái</label>
            <select name="status" id="status" class="form-select">
                <option value="1" {{ $flashSale->status ? 'selected' : '' }}>Đang chạy</option>
                <option value="0" {{ !$flashSale->status ? 'selected' : '' }}>Tắt</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Hiển thị ngoài trang người dùng</label>
            <select name="is_show" class="form-select">
                <option value="1" {{ $flashSale->is_show ? 'selected' : '' }}>Hiển thị</option>
                <option value="0" {{ !$flashSale->is_show ? 'selected' : '' }}>Không hiển thị</option>
            </select>
        </div>
        <div class="form-actions">
            <button type="submit">Cập nhật</button>
            <a href="{{ route('admin.flash-sales.index') }}" class="btn btn-secondary">Quay lại</a>

        </div>
        {{-- <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('admin.flash-sales.index') }}" class="btn btn-secondary">Quay lại</a> --}}
    </form>
</div>
@endsection
