@extends('layouts.layout')

@section('title', 'Chỉnh sửa sân cầu lông')

@section('content')

<div class="head-title">
        <div class="left">
            <h1>Chỉnh sửa sân</h1>
            <ul class="breadcrumb">
                <li><a href="#">Quản lí sân</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Chỉnh sửa sân</a></li>
            </ul>
        </div>
        <a href="{{ route('admin.courts.index') }}" class="btn-download">
            <span class="text">Quay lại</span>
	</a>
    </div>
<div class="form-add">
    <h1>Chỉnh sửa sân: {{ $court->Name }}</h1>

    <form action="{{ route('admin.courts.update', $court->Courts_ID) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="Name">Tên sân</label>
            <input type="text" name="Name" id="Name" value="{{ old('Name', $court->Name) }}" required>
        </div>

        <div class="form-group">
            <label for="Location">Vị trí</label>
            <input type="text" name="Location" id="Location" value="{{ old('Location', $court->Location) }}" required>
        </div>

        <div class="form-group">
            <label for="Court_type">Loại sân</label>
            <input type="text" name="Court_type" id="Court_type" value="{{ old('Court_type', $court->Court_type) }}" required>
        </div>

        <div class="form-group">
            <label for="Price_per_hour">Giá/giờ (VNĐ)</label>
            <input type="number" name="Price_per_hour" id="Price_per_hour" step="1000" value="{{ old('Price_per_hour', $court->Price_per_hour) }}" required>
        </div>

        <div class="form-group">
            <label for="Description">Mô tả</label>
            <textarea name="Description" id="Description" rows="4">{{ old('Description', $court->Description) }}</textarea>
        </div>

        <div class="form-group">
            <label for="Image">Hình ảnh hiện tại</label>
            @if ($court->Image)
                <img src="{{ asset($court->Image) }}" alt="Court Image" class="image-preview">
            @else
                <span>Không có ảnh</span>
            @endif

        <div class="form-group">
            <label for="Image">Hình ảnh mới (tùy chọn)</label>
            <input type="file" name="Image" id="Image" accept="image/*">
        </div>

        <div class="form-group">
            <label for="Status">Trạng thái</label>
            <select name="Status" id="Status" required>
                <option value="1" {{ $court->Status == 1 ? 'selected' : '' }}>Hiển thị</option>
                <option value="0" {{ $court->Status == 0 ? 'selected' : '' }}>Ẩn</option>
            </select>
        </div>

        <div class="form-group">
            <label for="open_time">Giờ mở cửa</label>
            <input type="time" name="open_time" id="open_time" value="{{ old('open_time', $court->open_time ?? '06:00') }}" required>
        </div>
        <div class="form-group">
            <label for="close_time">Giờ đóng cửa</label>
            <input type="time" name="close_time" id="close_time" value="{{ old('close_time', $court->close_time ?? '22:00') }}" required>
        </div>

        <div class="form-group">
            <label for="location_id">Địa điểm</label>
            <select id="location_id" name="location_id" required>
                <option value="">-- Chọn địa điểm --</option>
                @foreach($locations as $loc)
                    <option value="{{ $loc->id }}" {{ old('location_id', $court->location_id) == $loc->id ? 'selected' : '' }}>{{ $loc->name }}</option>
                @endforeach
            </select>
            @error('location_id') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-save">Cập nhật</button>
        </div>
    </form>
</div>
@endsection
