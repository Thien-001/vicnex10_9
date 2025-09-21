@extends('layouts.layout')

@section('title', 'Sửa Banner')

@section('content')
<style>
    .banner-form-container {
        /* max-width: 600px; */
        margin: 40px auto;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 24px rgba(1,84,185,0.10);
        padding: 36px 32px 28px 32px;
    }
    .banner-form-container h2 {
        /* color: #0154b9; */
        font-weight: 700;
        margin-bottom: 28px;
        text-align: center;
    }
    .form-label {
        font-weight: 600;
        /* color: #0154b9; */
    }
    .form-control, .form-check-input {
        border-radius: 7px;
        border: 1px solid #e0e7ff;
        font-size: 16px;
    }
    .form-control:focus {
        border-color: #3bb2ff;
        box-shadow: 0 0 0 2px #3bb2ff33;
    }
    .btn-success {
        background: linear-gradient(90deg,#0154b9 0%,#3bb2ff 100%);
        border: none;
        border-radius: 8px;
        font-weight: 700;
        padding: 10px 28px;
        font-size: 16px;
        margin-right: 10px;
        box-shadow: 0 2px 8px rgba(1,84,185,0.12);
        transition: background 0.2s;
    }
    .btn-success:hover {
        background: linear-gradient(90deg,#3bb2ff 0%,#0154b9 100%);
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
    .current-banner-img {
        display: block;
        margin: 0 auto 16px auto;
        border-radius: 10px;
        box-shadow: 0 2px 8px #e0e7ff;
        max-width: 100%;
        height: auto;
    }
    .form-check-label {
        font-weight: 500;
        color: #1976d2;
    }
</style>
<div class="banner-form-container">
    <h2>Sửa Banner</h2>
    <form action="{{ route('admin.banner.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3 text-center">
            <label class="form-label">
                Ảnh Banner hiện tại
                @if($banner->position == 1)
                    <span class="badge bg-primary ms-2">Ảnh chính</span>
                @elseif($banner->position == 2)
                    <span class="badge bg-info ms-2">Ảnh phụ 1</span>
                @elseif($banner->position == 3)
                    <span class="badge bg-secondary ms-2">Ảnh phụ 2</span>
                @endif
            </label><br>
            @if($banner->image_url)
                    <img src="{{ asset($banner->image_url) }}"
                        alt="Banner"
                        width="220"
                        class="current-banner-img mb-2">
                @endif

            <input type="file" class="form-control mt-2" name="image_url">
            <small class="text-muted">Chọn ảnh mới nếu muốn thay đổi</small>
        </div>
        <div class="mb-3">
            <label for="link" class="form-label">Link (nếu có)</label>
            <input type="text" class="form-control" id="link" name="link" value="{{ $banner->link }}">
        </div>
        <div class="mb-3">
            <label for="position" class="form-label">Vị trí</label>
            <input type="number" class="form-control" id="position" name="position" value="{{ $banner->position }}" min="1">
        </div>
        {{-- <div class="mb-3">
            <label for="button_text" class="form-label">Nội dung nút (nếu có)</label>
            <input type="text" class="form-control" id="button_text" name="button_text" value="{{ $banner->button_text }}">
        </div> --}}
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" {{ $banner->is_active ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">Hiển thị banner</label>
        </div>
        <div class="form-actions">
            <button type="submit">Cập nhật</button>
            <a href="{{ route('admin.banner.index') }}" class="btn btn-secondary px-4">Quay lại</a>

        </div>
        {{-- <div class="text-center">
            <button type="submit" class="btn btn-success">Cập nhật</button>
            <a href="{{ route('admin.banner.index') }}" class="btn btn-secondary">Quay lại</a>
        </div> --}}
    </form>
</div>
@endsection
