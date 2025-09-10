@extends('layouts.layout')

@section('title', 'Thêm sân cầu lông')

@section('content')
<style>
.form-add {
    max-width: 520px;
    margin: 32px auto;
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 4px 24px rgba(1,84,185,0.08);
    padding: 32px 28px;
}
.form-add h2 {
    color: #0154b9;
    font-weight: 700;
    margin-bottom: 28px;
    text-align: center;
}
.form-group {
    margin-bottom: 18px;
}
.form-group label {
    font-weight: 600;
    color: #0154b9;
    margin-bottom: 6px;
    display: block;
}
.form-group input,
.form-group select,
.form-group textarea {
    border-radius: 8px;
    border: 1px solid #e0e7ff;
    font-size: 16px;
    padding: 10px 12px;
    width: 100%;
    background: #f6f8fc;
    transition: border 0.2s;
}
.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    border-color: #0154b9;
    background: #fff;
}
.form-note {
    font-size: 13px;
    color: #888;
    font-weight: 400;
}
.form-error {
    color: #e53935;
    font-size: 14px;
    margin-top: 4px;
}
.form-actions {
    text-align: center;
    margin-top: 18px;
}
.form-actions button {
    background: linear-gradient(90deg,#0154b9 0%,#3bb2ff 100%);
    border: none;
    border-radius: 8px;
    font-weight: 700;
    padding: 12px 32px;
    font-size: 17px;
    color: #fff;
    box-shadow: 0 2px 8px rgba(1,84,185,0.12);
    transition: background 0.2s;
    cursor: pointer;
}
.form-actions button:hover {
    background: linear-gradient(90deg,#3bb2ff 0%,#0154b9 100%);
}
.image-preview {
    display: block;
    margin: 8px 0 0 0;
    max-width: 160px;
    border-radius: 8px;
    box-shadow: 0 2px 8px #0154b91a;
}
@media (max-width: 600px) {
    .form-add { padding: 12px 4px; }
}
</style>

<div class="head-title">
    <div class="left">
        <h1>Thêm sân</h1>
        <ul class="breadcrumb">
            <li><a href="#">Quản lí sân</a></li>
            <li><i class='bx bx-chevron-right'></i></li>
            <li><a class="active" href="#">Thêm sân</a></li>
        </ul>
    </div>
    <a href="{{ route('admin.courts.index') }}" class="btn-download">
        <span class="text">Quay lại</span>
    </a>
</div>

<div class="form-add">
    <h2>Thêm Sân Cầu Lông</h2>

    @if ($errors->any())
        <div style="background:#fee2e2; color:#b91c1c; padding:12px 16px; border-radius:6px; margin-bottom:20px;">
            <strong>Có lỗi xảy ra:</strong>
            <ul style="margin-top:8px; list-style: disc; padding-left:20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.courts.store') }}" method="POST" enctype="multipart/form-data" onsubmit="return validateImage()">
        @csrf

        <div class="form-group">
            <label for="Name">Tên sân</label>
            <input type="text" id="Name" name="Name" value="{{ old('Name') }}" required placeholder="Nhập tên sân...">
            @error('Name') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label for="location_id">Địa điểm</label>
            <select id="location_id" name="location_id" required>
                <option value="">-- Chọn địa điểm --</option>
                @foreach($locations as $loc)
                    <option value="{{ $loc->id }}" {{ old('location_id') == $loc->id ? 'selected' : '' }}>{{ $loc->name }}</option>
                @endforeach
            </select>
            @error('location_id') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label for="Court_type">Loại sân</label>
            <select id="Court_type" name="Court_type" required>
                <option value="">-- Chọn loại sân --</option>
                <option value="Sân thảm" {{ old('Court_type') == 'Sân thảm' ? 'selected' : '' }}>Sân thảm</option>
                <option value="Sân gỗ" {{ old('Court_type') == 'Sân gỗ' ? 'selected' : '' }}>Sân gỗ</option>
                <option value="Sân xi măng" {{ old('Court_type') == 'Sân xi măng' ? 'selected' : '' }}>Sân xi măng</option>
                <option value="Sân nhựa tổng hợp" {{ old('Court_type') == 'Sân nhựa tổng hợp' ? 'selected' : '' }}>Sân nhựa tổng hợp</option>
            </select>
            @error('Court_type') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label for="Price_per_hour">Giá/giờ (VNĐ)</label>
            <input type="number" id="Price_per_hour" name="Price_per_hour" value="{{ old('Price_per_hour') }}" min="0" step="1000" required placeholder="Nhập giá/giờ...">
            @error('Price_per_hour') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label for="Status">Trạng thái</label>
            <select id="Status" name="Status">
                <option value="1" {{ old('Status', 1) == 1 ? 'selected' : '' }}>Hoạt động</option>
                <option value="0" {{ old('Status') == "0" ? 'selected' : '' }}>Tạm ngưng</option>
            </select>
            @error('Status') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label for="Image">Ảnh sân <span class="form-note">(tùy chọn)</span></label>
            <input type="file" id="Image" name="Image" accept="image/*" onchange="previewImage(event)">
            <img id="preview" class="image-preview" style="display:none;" />
            @error('Image') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label for="Description">Mô tả</label>
            <textarea id="Description" name="Description" rows="4" placeholder="Mô tả chi tiết về sân...">{{ old('Description') }}</textarea>
            @error('Description') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label for="open_time">Giờ mở cửa</label>
            <input type="time" name="open_time" id="open_time" value="{{ old('open_time', '06:00') }}" required>
        </div>
        <div class="form-group">
            <label for="close_time">Giờ đóng cửa</label>
            <input type="time" name="close_time" id="close_time" value="{{ old('close_time', '22:00') }}" required>
        </div>

        <div class="form-actions">
            <button type="submit">Thêm sân</button>
        </div>
    </form>
</div>

<script>
function previewImage(event) {
    const [file] = event.target.files;
    const preview = document.getElementById('preview');
    if (file) {
        preview.src = URL.createObjectURL(file);
        preview.style.display = 'block';
    } else {
        preview.src = '';
        preview.style.display = 'none';
    }
}
function validateImage() {
    const input = document.getElementById('Image');
    if (input.files.length > 0) {
        const file = input.files[0];
        if (!file.type.startsWith('image/')) {
            alert('Vui lòng chọn đúng định dạng ảnh!');
            return false;
        }
        if (file.size > 2 * 1024 * 1024) {
            alert('Ảnh không được vượt quá 2MB!');
            return false;
        }
    }
    return true;
}
</script>
@endsection
