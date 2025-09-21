@extends('layouts.layout')
@section('content')
<style>
.popup-table-container {
    /* max-width: 1100px; */
    margin: 36px auto;
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 4px 24px rgba(1,84,185,0.08);
    padding: 32px 28px;
}
.popup-table-container h2 {
    /* color: #0154b9; */
    font-weight: 700;
    margin-bottom: 28px;
    text-align: center;
}
.popup-table-container .btn-success {
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
.popup-table-container .btn-success:hover {
    background: linear-gradient(90deg,#3bb2ff 0%,#0154b9 100%);
}
.popup-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    background: #f6f8fc;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px #e0e7ff;
}
.popup-table th, .popup-table td {
    padding: 12px 16px;
    font-size: 16px;
    text-align: center;
    border-bottom: 1px solid #e0e7ff;
    vertical-align: middle;
}
.popup-table th {
    background: #e0e7ff;
    color: #0154b9;
    font-weight: 600;
}
.popup-table tr:last-child td {
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
        <h1>Popup</h1>
        <ul class="breadcrumb">
            <li><a href="#">Popup</a></li>
            <li><i class='bx bx-chevron-right'></i></li>
            <li><a class="active" href="#">Danh sách Popup</a></li>
        </ul>
    </div>
    <a href="{{ route('admin.popup.create') }}" class="btn-download">
        <span class="text">+ Thêm popup mới</span>
    </a>
</div>
<div class="popup-table-container">
    <h2>Danh sách Popup</h2>
    {{-- <a href="{{ route('admin.popup.create') }}" class="btn btn-success mb-3">+ Thêm popup</a> --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="popup-table">
        <thead>
            <tr>
                <th>Ảnh</th>
                <th>Link</th>
                <th>Tiêu đề</th>
                <th>Nội dung</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach($popups as $popup)
            <tr>
                <td>
                    @if($popup->image_url)

                        <img src="{{ asset($popup->image_url) }}" alt="Popup" width="120">
                    @else
                        Không có ảnh
                    @endif
                </td>
                <td>
                    {{ asset(ltrim($popup->image_url, '/')) }}
                </td>

                <td>{{ $popup->title }}</td>
                <td>{{ $popup->content }}</td>
                <td>
                    @if($popup->is_active)
                        <span class="badge bg-success1">Hiện</span>
                    @else
                        <span class="badge bg-secondary1">Ẩn</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('admin.popup.edit', $popup->id) }}" class="btn btn-primary btn-sm">Sửa</a>
                    <form action="{{ route('admin.popup.destroy', $popup->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Bạn chắc chắn muốn xóa?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">Xóa</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
