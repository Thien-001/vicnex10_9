@extends('layouts.layout')

@section('content')
<style>
.body-content table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 12px rgba(1,84,185,0.07);
    margin-top: 18px;
    font-size: 15px;
}
.body-content th, .body-content td {
    padding: 12px 14px;
    text-align: center;
    border-bottom: 1px solid #e3f0ff;
}
.body-content th {
    background: #f5faff;
    color: #0154b9;
    font-weight: 600;
    font-size: 16px;
}
.body-content tr:last-child td {
    border-bottom: none;
}
.body-content tr:hover {
    background: #f0f7ff;
}
.action-buttons {
    display: flex;
    gap: 8px;
    justify-content: center;
}
.admin-button-table {
    background: #0154b9;
    color: #fff;
    border: none;
    border-radius: 6px;
    padding: 6px 16px;
    font-size: 14px;
    cursor: pointer;
    transition: background 0.2s;
}
.admin-button-table.btn-delete {
    background: #e74c3c;
}
.admin-button-table:hover {
    background: #013e8a;
}
.admin-button-table.btn-delete:hover {
    background: #c0392b;
}
.body-content .pagination {
    display: flex;
    justify-content: center;
    margin-top: 18px;
}
.body-content .pagination li {
    margin: 0 3px;
}
.body-content .pagination .active span,
.body-content .pagination li a:hover {
    background: #0154b9;
    color: #fff !important;
    border-radius: 5px;
}
.form-group {
    margin-top: 32px;
    background: #fafdff;
    border: 1.5px solid #e3f0ff;
    border-radius: 10px;
    padding: 18px 22px;
    max-width: 600px;
    box-shadow: 0 1px 6px rgba(1,84,185,0.06);
}
.form-group label {
    font-weight: 500;
    color: #0154b9;
    margin-bottom: 8px;
    display: block;
}
.form-group input[type="radio"],
.form-group input[type="checkbox"] {
    accent-color: #0154b9;
    margin-right: 6px;
}
.form-group small {
    display: block;
    margin-top: 8px;
    color: #888;
    font-size: 13px;
}
.alert-success {
    background: #e6f9e6;
    color: #1a7f37;
    border: 1px solid #b6e2b6;
    border-radius: 6px;
    padding: 10px 18px;
    margin: 16px 0;
    font-size: 15px;
}
</style>

<div class="head-title">
    <div class="left">
        <h1>Voucher</h1>
        <ul class="breadcrumb">
            <li><a href="#">Voucher</a></li>
            <li><i class='bx bx-chevron-right'></i></li>
            <li><a class="active" href="#">Danh sách voucher</a></li>
        </ul>
    </div>
    <a href="{{ route('admin.vouchers.create') }}" class="btn-download">
        <span class="text">+ Thêm voucher mới</span>
    </a>
</div>
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="body-content">
    <h1>Quản lý Voucher</h1>
    <table>
        <thead>
            <tr>
                <th>STT</th>
                <th>Mã</th>
                <th>Loại giảm</th>
                <th>Giá trị</th>
                <th>Lượt dùng tối đa</th>
                <th>Hạn dùng</th>
                <th>Áp dụng cho</th>
                <th>Ngày đã sử dụng hết</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($vouchers as $voucher)
            <tr>
                <td>{{ $loop->iteration + ($vouchers->currentPage() - 1) * $vouchers->perPage() }}</td>
                <td>{{ $voucher->code }}</td>
                <td>{{ $voucher->discount_type == 'percentage' ? 'Phần trăm' : 'Cố định' }}</td>
                <td>
                    {{ $voucher->discount_type == 'percentage'
                        ? $voucher->discount_value . '%'
                        : number_format($voucher->discount_value) . '₫' }}
                </td>
                <td>{{ $voucher->max_uses ?? 'Không giới hạn' }}</td>
                <td>{{ $voucher->expires ?? 'Không có' }}</td>
                <td>
                    @if($voucher->applies_to == 'all')
                        Tất cả
                    @elseif($voucher->applies_to == 'booking')
                        Đặt sân
                    @else
                        @php
                            $ids = explode(',', $voucher->applies_to);
                            $names = \App\Models\Category::whereIn('Categories_ID', $ids)->pluck('Name')->toArray();
                        @endphp
                        {{ implode(', ', $names) }}
                    @endif
                </td>
                <td>{{ $voucher->paid_at ?? 'Chưa hết' }}</td>
                <td class="action-buttons">
                    <a href="{{ route('admin.vouchers.edit', $voucher->id) }}" class="btn-edit">
                        <button class="admin-button-table">Sửa</button>
                    </a>
                    <form action="{{ route('admin.vouchers.destroy', $voucher->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="admin-button-table btn-delete" onclick="return confirm('Bạn có chắc muốn xoá?')">Xoá</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div style="margin-top: 20px">
        {{ $vouchers->appends(request()->query())->links() }}
    </div>
</div>
    
</div>
@endsection
