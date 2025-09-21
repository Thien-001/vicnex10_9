@extends('layouts.layout')

@section('title', 'Thêm Banner mới')

@section('content')
<div class="banner-form-container">
    <h2 class="mb-4">Thêm Banner mới</h2>
    <form action="{{ route('admin.banner.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="image_url" class="form-label">Ảnh Banner</label>
            <input type="file" class="form-control" id="image_url" name="image_url" required>
        </div>
        <div class="mb-3">
            <label for="link" class="form-label">Link (nếu có)</label>
            <input type="text" class="form-control" id="link" name="link" placeholder="https://...">
        </div>
        <div class="mb-3">
            <label for="position" class="form-label">Vị trí</label>
            <select class="form-control" id="position" name="position" required>
                <option value="1">Ảnh chính</option>
                <option value="2">Ảnh phụ 1</option>
                <option value="3">Ảnh phụ 2</option>
            </select>
        </div>
        {{-- <div class="mb-3">
            <label for="button_text" class="form-label">Nội dung nút (nếu có)</label>
            <input type="text" class="form-control" id="button_text" name="button_text" placeholder="Ví dụ: Xem ngay">
        </div> --}}
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" checked>
            <label class="form-check-label" for="is_active">Hiển thị banner</label>
        </div>
        <div class="form-actions">
            <button type="submit">Thêm</button>
            <a href="{{ route('admin.banner.index') }}" class="btn btn-secondary px-4">Quay lại</a>

        </div>
        {{-- <div class="d-flex justify-content-center gap-3 mt-4">
            <button type="submit" class="btn btn-success px-4">Thêm</button>
            <a href="{{ route('admin.banner.index') }}" class="btn btn-secondary px-4">Quay lại</a>
        </div> --}}
    </form>
</div>
@endsection

<style>
.banner-form-container {
    /* max-width: 520px; */
    margin: 48px auto;
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 6px 32px rgba(1,84,185,0.10);
    padding: 38px 32px 32px 32px;
}
.banner-form-container h2 {
    /* color: #0154b9; */
    font-weight: 700;
    margin-bottom: 32px;
    text-align: center;
    letter-spacing: 1px;
}
.form-label {
    font-weight: 600;
    /* color: #0154b9; */
    margin-bottom: 6px;
}
.form-control, .form-check-input {
    border-radius: 8px;
    border: 1px solid #e0e7ff;
    font-size: 16px;
    padding: 10px 14px;
    background: #f6f8fc;
    transition: border-color 0.2s, box-shadow 0.2s;
}
.form-control:focus {
    border-color: #3bb2ff;
    box-shadow: 0 0 0 2px #3bb2ff33;
    background: #fff;
}
.form-check-input {
    width: 20px;
    height: 20px;
    margin-top: 4px;
}
.form-check-label {
    font-size: 16px;
    color: #222;
    margin-left: 8px;
    font-weight: 500;
}
.btn-success, .btn-secondary {
    border-radius: 8px;
    font-weight: 700;
    font-size: 16px;
    padding: 10px 32px;
    box-shadow: 0 2px 8px rgba(1,84,185,0.10);
    border: none;
    transition: background 0.2s, color 0.2s;
}
.btn-success {
    background: linear-gradient(90deg,#0154b9 0%,#3bb2ff 100%);
    color: #fff;
}
.btn-success:hover {
    background: linear-gradient(90deg,#3bb2ff 0%,#0154b9 100%);
    color: #fff;
}

@media (max-width: 600px) {
    .banner-form-container {
        padding: 18px 6px 18px 6px;
    }
    .btn-success, .btn-secondary {
        padding: 10px 18px;
        font-size: 15px;
    }
}
.banner-form-container .btn-secondary {
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
.banner-form-container .btn-secondary:hover {
    background: #4a65d1;
    /* color: #003c7a; */
}
</style>
