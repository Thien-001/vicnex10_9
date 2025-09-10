@extends('layouts.layout')

@section('title', 'Quản lý chuyên gia')

@section('content')
<style>
.table-expert {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 4px 24px rgba(1,84,185,0.08);
    overflow: hidden;
}
.table-expert th, .table-expert td {
    padding: 14px 12px;
    border-bottom: 1px solid #e3e8f0;
    text-align: left;
    font-size: 15px;
}
.table-expert th {
    background: #f4f9fd;
    font-weight: 700;
    color: #0154b9;
}
.table-expert tr:last-child td {
    border-bottom: none;
}
.table-expert img {
    border-radius: 8px;
    max-width: 70px;
    max-height: 70px;
    object-fit: cover;
    box-shadow: 0 2px 8px #0154b91a;
    cursor: pointer;
    transition: transform 0.18s;
}
.table-expert img:hover {
    transform: scale(1.08);
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
    .table-expert th, .table-expert td { font-size: 13px; padding: 8px 4px; }
    .table-expert img { max-width: 50px; max-height: 50px; }
}
</style>

<div class="head-title">
    <div class="left">
        <h1>Danh sách chuyên gia</h1>
        <ul class="breadcrumb">
            <li><a href="#">Quản lí chuyên gia</a></li>
            <li><i class='bx bx-chevron-right'></i></li>
            <li><a class="active" href="#">Danh sách chuyên gia</a></li>
        </ul>
    </div>
    <a href="{{ route('admin.experts.create') }}" class="btn-download">
        <span class="text">+ Thêm chuyên gia</span>
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success" style="margin: 15px 0;">{{ session('success') }}</div>
@endif

<div class="body-content">
    <div style="overflow-x:auto;">
        <table class="table-expert">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Ảnh</th>
                    <th>Tên chuyên gia</th>
                    <th>Chức danh</th>
                    <th>Tiểu sử</th>
                    <th>Ngày tạo</th>
                    <th class="text-center">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($experts as $index => $expert)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            @if ($expert->photo)
                                <img src="{{ asset($expert->photo) }}" alt="Ảnh chuyên gia" title="Xem ảnh lớn" onclick="window.open('{{ asset($expert->photo) }}','_blank')" />
                            @else
                                <span>Không có ảnh</span>
                            @endif
                        </td>
                        <td>{{ $expert->name }}</td>
                        <td>{{ $expert->position }}</td>
                        <td style="max-width:220px;">{{ Str::limit($expert->bio, 60) }}</td>
                        <td>{{ $expert->created_at->format('d/m/Y') }}</td>
                        <td class="action-buttons text-center">
                            <a href="{{ route('admin.experts.edit', $expert) }}" class="admin-button-table">Sửa</a>
                            <form action="{{ route('admin.experts.destroy', $expert) }}" method="POST" style="display:inline;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa chuyên gia này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="admin-button-table btn-delete">Xoá</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">Chưa có chuyên gia nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection