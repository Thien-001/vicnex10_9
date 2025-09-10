
@extends('layouts.layout')

@section('content')
<style>
    .location-edit-container {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 24px #0154b91a;
        padding: 32px 24px;
        margin-top: 32px;
        max-width: 540px;
        margin-left: auto;
        margin-right: auto;
    }
    .location-edit-container h2 {
        font-weight: 700;
        color: #0154b9;
        margin-bottom: 24px;
    }
    .form-group label {
        font-weight: 600;
        color: #0154b9;
    }
    .form-control {
        border-radius: 7px;
        border: 1.5px solid #e0e7ef;
        padding: 10px 12px;
        margin-bottom: 14px;
        font-size: 15px;
        background: #f6f8fa;
        color: #222;
        transition: border 0.18s;
    }
    .form-control:focus {
        border: 1.5px solid #1976d2;
        background: #fff;
        outline: none;
    }
    .btn-primary, .btn-secondary {
        border: none;
        border-radius: 6px;
        padding: 9px 22px;
        font-weight: 600;
        font-size: 15px;
        margin-right: 8px;
        transition: background 0.18s, color 0.18s;
    }
    .btn-primary { background: #0154b9; color: #fff; }
    .btn-primary:hover { background: #1976d2; }
    .btn-secondary { background: #e0e7ef; color: #0154b9; }
    .btn-secondary:hover { background: #b6c6e3; color: #222; }
    .text-danger { color: #e53935; font-size: 14px; }
    @media (max-width: 600px) {
        .location-edit-container { padding: 10px 2px; }
        .form-control { font-size: 13px; }
    }
</style>
<div class="location-edit-container">
    <h2>Sửa địa điểm</h2>
    <form action="{{ route('admin.locations.update', $location->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="form-group">
            <label>Tên địa điểm</label>
            <input type="text" name="name" class="form-control" required value="{{ old('name', $location->name) }}">
            @error('name') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
            <label>Địa chỉ</label>
            <input type="text" name="address" class="form-control" required value="{{ old('address', $location->address) }}">
            @error('address') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
            <label>Mô tả</label>
            <textarea name="description" class="form-control">{{ old('description', $location->description) }}</textarea>
        </div>
        <button class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('admin.locations.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection