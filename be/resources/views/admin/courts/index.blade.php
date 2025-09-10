@extends('layouts.layout')

@section('title', 'Quản lý sân cầu lông')

@section('content')
<style>
.table-court {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 4px 24px rgba(1,84,185,0.08);
    overflow: hidden;
}
.table-court th, .table-court td {
    padding: 14px 12px;
    border-bottom: 1px solid #e3e8f0;
    text-align: left;
    font-size: 15px;
}
.table-court th {
    background: #f4f9fd;
    font-weight: 700;
    color: #0154b9;
}
.table-court tr:last-child td {
    border-bottom: none;
}
.table-court img {
    border-radius: 8px;
    max-width: 90px;
    max-height: 60px;
    object-fit: cover;
    box-shadow: 0 2px 8px #0154b91a;
    cursor: pointer;
    transition: transform 0.18s;
}
.table-court img:hover {
    transform: scale(1.08);
}
.status-active {
    color: #22c55e;
    font-weight: 700;
}
.status-inactive {
    color: #ef4444;
    font-weight: 700;
}
.action-buttons {
    display: flex;
    gap: 8px;
    justify-content: center;
}
.admin-button-table {
    background: #f4f9fd;
    border: 1.5px solid #0154b9;
    color: #0154b9;
    border-radius: 6px;
    padding: 6px 14px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.18s, color 0.18s;
}
.admin-button-table:hover {
    background: #0154b9;
    color: #fff;
}
.btn-delete {
    background: #fee2e2;
    color: #b91c1c;
    border: 1.5px solid #f87171;
}
.btn-delete:hover {
    background: #f87171;
    color: #fff;
}
@media (max-width: 900px) {
    .table-court th, .table-court td { font-size: 13px; padding: 8px 4px; }
    .table-court img { max-width: 60px; max-height: 40px; }
}
</style>

<div class="head-title">
    <div class="left">
        <h1>Danh sách sân</h1>
        <ul class="breadcrumb">
            <li><a href="#">Quản lí sân</a></li>
            <li><i class='bx bx-chevron-right'></i></li>
            <li><a class="active" href="#">Danh sách sân</a></li>
        </ul>
    </div>
    <a href="{{ route('admin.courts.create') }}" class="btn-download">
        <span class="text">+ Thêm sân mới</span>
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success" style="margin: 15px 0;">{{ session('success') }}</div>
@endif

<div class="body-content">
    <div style="overflow-x:auto;">
        <table class="table-court">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Hình ảnh</th>
                    <th>Tên sân</th>
                    <th>Vị trí</th>
                    <th>Loại</th>
                    <th class="text-right">Giá/giờ</th>
                    <th>Giờ mở cửa</th>
                    <th>Giờ đóng cửa</th>
                    <th class="text-center">Trạng thái</th>
                    <th class="text-center">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($courts as $index => $court)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            @if ($court->Image)
                                <img src="{{ asset($court->Image) }}" alt="Image" title="Xem ảnh lớn" onclick="window.open('{{ asset($court->Image) }}','_blank')" />
                            @else
                                <span>Không có ảnh</span>
                            @endif
                        </td>
                        <td>{{ $court->Name }}</td>
                        <td>{{ $court->location->name ?? '-' }}</td>
                        <td>{{ $court->Court_type }}</td>
                        <td class="text-right">{{ number_format($court->Price_per_hour, 0, ',', '.') }} đ</td>
                        <td>{{ $court->open_time ?? '-' }}</td>
                        <td>{{ $court->close_time ?? '-' }}</td>
                        <td class="text-center">
                            @if($court->Status)
                                <span class="status-active">Hoạt động</span>
                            @else
                                <span class="status-inactive">Tạm ngưng</span>
                            @endif
                        </td>
                        <td class="action-buttons text-center">
                            <a href="{{ route('admin.courts.edit', $court->Courts_ID) }}" class="admin-button-table">Sửa</a>
                            @if(!$court->Status)
                                <form action="{{ route('admin.courts.confirm', $court->Courts_ID) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="admin-button-table" style="background:#e0fce0; color:#22c55e; border:1.5px solid #22c55e; margin-left:4px;"
                                        onclick="return confirm('Xác nhận duyệt sân này?')">
                                        Xác nhận
                                    </button>
                                </form>
                            @endif
                            <form action="{{ route('admin.courts.destroy', $court->Courts_ID) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="admin-button-table btn-delete" onclick="return confirm('Bạn có chắc chắn muốn xóa sân này?')">Xoá</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted">Chưa có sân nào được tạo.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div style="margin-top:18px;">{{ $courts->links() }}</div>
    </div>
</div>
@endsection
