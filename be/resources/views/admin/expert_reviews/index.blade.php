@extends('layouts.layout')

@section('title', 'Quản lý nhận xét chuyên gia')

@section('content')
<style>
.table-expert-review {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 4px 24px rgba(1,84,185,0.08);
    overflow: hidden;
}
.table-expert-review th, .table-expert-review td {
    padding: 14px 12px;
    border-bottom: 1px solid #e3e8f0;
    text-align: left;
    font-size: 15px;
}
.table-expert-review th {
    background: #f4f9fd;
    font-weight: 700;
    color: #0154b9;
}
.table-expert-review tr:last-child td {
    border-bottom: none;
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
</style>

<div class="head-title">
    <div class="left">
        <h1>Nhận xét chuyên gia</h1>
        <ul class="breadcrumb">
            <li><a href="#">Nhận xét chuyên gia</a></li>
            <li><i class='bx bx-chevron-right'></i></li>
            <li><a class="active" href="#">Danh sách nhận xét</a></li>
        </ul>
    </div>
    <a href="{{ route('admin.expert-reviews.create') }}" class="btn-download">
        <span class="text">+ Thêm nhận xét</span>
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success" style="margin: 15px 0;">{{ session('success') }}</div>
@endif

<div class="body-content">
    <div style="overflow-x:auto;">
        <table class="table-expert-review">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Sản phẩm</th>
                    <th>Chuyên gia</th>
                    <th>Nội dung</th>
                    <th>Ngày tạo</th>
                    <th class="text-center">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reviews as $index => $review)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            @if($review->product)
                                {{ $review->product->Name ?? '---' }}
                            @else
                                ---
                            @endif
                        </td>
                        <td>{{ $review->expert_name }}</td>
                        <td style="max-width:260px;">{{ Str::limit($review->content, 80) }}</td>
                        <td>{{ $review->created_at->format('d/m/Y') }}</td>
                        <td class="action-buttons text-center">
                            <a href="{{ route('admin.expert-reviews.edit', $review) }}" class="admin-button-table">Sửa</a>
                            <form action="{{ route('admin.expert-reviews.destroy', $review) }}" method="POST" style="display:inline;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa nhận xét này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="admin-button-table btn-delete">Xoá</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">Chưa có nhận xét nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection