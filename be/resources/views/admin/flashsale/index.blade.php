@extends('layouts.layout')

@section('content')
<style>
    .flashsale-table-container {
        /* max-width: 1500px; */
        margin: 36px auto;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 24px rgba(1,84,185,0.08);
        padding: 32px 28px;
    }
    .flashsale-table-container h2 {
        /* color: #0154b9; */
        font-weight: 700;
        margin-bottom: 28px;
        text-align: center;
    }
    .flashsale-table-container .btn-primary {
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
    .flashsale-table-container .btn-primary:hover {
        background: linear-gradient(90deg,#3bb2ff 0%,#0154b9 100%);
    }
    .flashsale-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        background: #f6f8fc;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px #e0e7ff;
    }
    .flashsale-table th, .flashsale-table td {
        padding: 12px 16px;
        font-size: 16px;
        text-align: center;
        border-bottom: 1px solid #e0e7ff;
    }
    .flashsale-table th {
        background: #e0e7ff;
        color: #0154b9;
        font-weight: 600;
    }
    .flashsale-table tr:last-child td {
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
    .btn-sm {
        padding: 6px 18px;
        font-size: 15px;
        border-radius: 7px;
        font-weight: 500;
        border: none;
        margin: 6px;
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
        <h1>Flash Sale</h1>
        <ul class="breadcrumb">
            <li><a href="#">Flash Sale</a></li>
            <li><i class='bx bx-chevron-right'></i></li>
            <li><a class="active" href="#">Danh sách Flash Sale</a></li>
        </ul>
    </div>
    <a href="{{ route('admin.flash-sales.create') }}" class="btn-download">
        <span class="text">+ Thêm Flash Sale mới</span>
    </a>
</div>
@if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
<div class="flashsale-table-container">
    <h2>Quản lý Flash Sale</h2>
    {{-- <a href="{{ route('admin.flash-sales.create') }}" class="btn btn-primary">+ Thêm Flash Sale mới</a> --}}
    <table class="flashsale-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Sản phẩm</th>
                <th>Giá gốc</th>
                <th>Giá sale</th>
                <th>Giảm (%)</th>
                <th>Bắt đầu</th>
                <th>Kết thúc</th>
                <th>Trạng thái</th>
                <th>Hiển thị ngoài trang người dùng</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach($flashSales as $fs)
            <tr>
                <td>{{ $fs->id }}</td>
                <td>{{ $fs->product->Name ?? '---' }}</td>
                <td>{{ number_format($fs->price_old) }}₫</td>
                <td style="color:#d32f2f;font-weight:600">{{ number_format($fs->price_sale) }}₫</td>
                <td>{{ $fs->discount ?? '-' }}</td>
                <td>{{ $fs->start_time }}</td>
                <td>{{ $fs->end_time }}</td>
                <td>
                    @if($fs->status)
                        <span class="badge bg-success1">Đang chạy</span>
                    @else
                        <span class="badge bg-secondary1">Tắt</span>
                    @endif
                </td>
                <td>
                    @if($fs->is_show)
                        <form action="{{ route('admin.flash-sales.update', $fs->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="is_show" value="0">
                            <button type="submit" class="badge bg-success1" style="border:none;cursor:pointer;">Đang hiển thị (Bấm để tắt)</button>
                        </form>
                    @else
                        <form action="{{ route('admin.flash-sales.update', $fs->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="is_show" value="1">
                            <button type="submit" class="badge bg-secondary1" style="border:none;cursor:pointer;">Không hiển thị (Bấm để bật)</button>
                        </form>
                    @endif
                </td>
                <td>
                    <a href="{{ route('admin.flash-sales.edit', $fs->id) }}" class="btn btn-sm btn-warning">Sửa</a>
                    <form action="{{ route('admin.flash-sales.destroy', $fs->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button onclick="return confirm('Xác nhận xóa?')" class="btn btn-sm btn-danger">Xóa</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
