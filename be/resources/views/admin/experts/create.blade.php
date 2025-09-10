@extends('layouts.layout')

@section('title', 'Thêm chuyên gia')

@section('content')
<style>
.form-expert {
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 4px 24px rgba(1,84,185,0.08);
    padding: 32px 28px;
    max-width: 600px;
    margin: 0 auto;
}
.form-expert label {
    font-weight: 600;
    color: #0154b9;
}
.form-expert input, .form-expert textarea {
    border-radius: 6px;
    border: 1.5px solid #e3e8f0;
    padding: 8px 12px;
    width: 100%;
    margin-bottom: 18px;
    font-size: 15px;
}
.form-expert textarea { min-height: 90px; }
.form-expert button {
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
.form-expert button:hover { background: #003c7e; }
.form-expert .btn-cancel {
    background: #f4f9fd;
    color: #0154b9;
    border: 1.5px solid #0154b9;
}
.form-expert .btn-cancel:hover {
    background: #0154b9;
    color: #fff;
}
</style>

<div class="head-title">
    <div class="left">
        <h1>Thêm chuyên gia</h1>
        <ul class="breadcrumb">
            <li><a href="{{ route('admin.experts.index') }}">Quản lí chuyên gia</a></li>
            <li><i class='bx bx-chevron-right'></i></li>
            <li><a class="active" href="#">Thêm chuyên gia</a></li>
        </ul>
    </div>
</div>

<div class="form-expert mt-4">
    <form action="{{ route('admin.experts.store') }}" method="POST">
        @csrf
        <label>Tên chuyên gia *</label>
        <input type="text" name="name" required value="{{ old('name') }}">

        <label>Ảnh (đường dẫn hoặc upload sau)</label>
        <input type="text" name="photo" value="{{ old('photo') }}">

        <label>Chức danh</label>
        <input type="text" name="position" value="{{ old('position') }}">

        <label>Tiểu sử</label>
        <textarea name="bio">{{ old('bio') }}</textarea>

        <button type="submit">Lưu</button>
        <a href="{{ route('admin.experts.index') }}" class="btn-cancel btn">Quay lại</a>
    </form>
</div>
@endsection