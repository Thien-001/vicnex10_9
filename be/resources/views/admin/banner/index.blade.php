@extends('layouts.layout')

@section('title', 'Quản lý Banner')

@section('content')
<style>
    .banner-table-container {
        /* max-width: 1100px; */
        margin: 36px auto;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 24px rgba(1,84,185,0.08);
        padding: 32px 28px;
    }
    .banner-table-container h2 {
        /* color: #0154b9; */
        font-weight: 700;
        margin-bottom: 28px;
        text-align: center;
    }
    .banner-table-container .btn-primary {
        background: linear-gradient(90deg,#0154b9 0%,#3bb2ff 100%);
        border: none;
        border-radius: 8px;
        font-weight: 700;
        padding: 10px 28px;
        font-size: 16px;
        margin-bottom: 18px;
        box-shadow: 0 2px 8px rgba(1,84,185,0.12);
        transition: background 0.2s;
    }
    .banner-table-container .btn-primary:hover {
        background: linear-gradient(90deg,#3bb2ff 0%,#0154b9 100%);
    }
    .banner-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        background: #f6f8fc;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px #e0e7ff;
    }
    .banner-table th, .banner-table td {
        padding: 12px 16px;
        font-size: 16px;
        text-align: center;
        border-bottom: 1px solid #e0e7ff;
        vertical-align: middle;
    }
    .banner-table th {
        background: #e0e7ff;
        color: #0154b9;
        font-weight: 600;
    }
    .banner-table tr:last-child td {
        border-bottom: none;
    }
    .badge {
        padding: 6px 16px;
        border-radius: 8px;
        font-size: 15px;
        font-weight: 600;
    }
    .bg-success1 {
        background: #d1fae5;
        color: #059669;
    }
    .bg-secondary1 {
        background: #e5e7eb;
        color: #6b7280;
    }
    .bg-primary {
        background: #e0e7ff;
        /* color: #0154b9; */
    }
    .bg-info {
        background: #bae6fd;
        color: #0369a1;
    }
    .btn-sm {
        padding: 6px 18px;
        font-size: 15px;
        border-radius: 7px;
        font-weight: 500;
        border: none;
        margin-right: 6px;
        transition: background 0.2s;
    }
    .btn-warning {
        background: #3498db;
        color: #fff;
    }
    .btn-warning:hover {
        background: #2980b9;
        color: #e3e3e3;
    }
    .btn-danger {
        background: #e74c3c;
        color: #fff;
    }
    .btn-danger:hover {
        background: #c0392b;
        color: #e3e3e3;
    }
</style>
<div class="head-title">
    <div class="left">
        <h1>Banner</h1>
        <ul class="breadcrumb">
            <li><a href="#">Banner</a></li>
            <li><i class='bx bx-chevron-right'></i></li>
            <li><a class="active" href="#">Danh sách Banner</a></li>
        </ul>
    </div>
    <a href="{{ route('admin.banner.create') }}" class="btn-download">
        <span class="text">+ Thêm Banner mới</span>
    </a>
</div>
@if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
<div class="banner-table-container">
    <h2>Quản lý Banner</h2>
    {{-- <a href="{{ route('admin.banner.create') }}" class="btn btn-primary">+ Thêm Banner mới</a> --}}
    <table class="banner-table">
        <thead>
            <tr>
                <th>Ảnh</th>
                <th>Link</th>
                <th>Vị trí</th>
                <th>Trạng thái</th>
                {{-- <th>Nút</th> --}}
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($banners as $banner)
            <tr>
                <td>
                    @if($banner->image_url)
                        <img src="{{ asset($banner->image_url) }}" alt="Banner" width="120">
                    @endif
                </td>
                <td>{{ $banner->image_url }}</td>
                <td>
                    @if($banner->position == 1)
                        <span class="badge bg-primary">Ảnh chính</span>
                    @elseif($banner->position == 2)
                        <span class="badge bg-info">Ảnh phụ 1</span>
                    @elseif($banner->position == 3)
                        <span class="badge bg-secondary1">Ảnh phụ 2</span>
                    @else
                        <span class="badge bg-secondary1">Khác</span>
                    @endif
                </td>
                <td>
                    @if($banner->is_active)
                        <span class="badge bg-success1">Hiện</span>
                    @else
                        <span class="badge bg-secondary1">Ẩn</span>
                    @endif
                </td>
                {{-- <td>{{ $banner->button_text }}</td> --}}
                <td>
                    <a href="{{ route('admin.banner.edit', $banner->id) }}" class="btn btn-sm btn-warning">Sửa</a>
                    <form action="{{ route('admin.banner.destroy', $banner->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Bạn chắc chắn muốn xóa?')">Xóa</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
