@extends('layouts.layout')

@section('content')
<style>
    .location-table-container {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 24px #0154b91a;
        padding: 32px 24px;
        margin-top: 32px;
    }
    .location-table-container h2 {
        font-weight: 700;
        color: #0154b9;
        margin-bottom: 24px;
    }
    .table-location {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 18px;
    }
    .table-location th, .table-location td {
        border: 1px solid #e0e7ef;
        padding: 12px 10px;
        text-align: left;
        font-size: 15px;
    }
    .table-location th {
        background: #e0f2fe;
        color: #0154b9;
        font-weight: 700;
    }
    .table-location tr:nth-child(even) {
        background: #f6f8fa;
    }
    .btn-primary, .btn-warning, .btn-danger, .btn-secondary {
        border: none;
        border-radius: 6px;
        padding: 7px 16px;
        font-weight: 600;
        font-size: 14px;
        margin-right: 4px;
        transition: background 0.18s, color 0.18s;
    }
    .btn-primary { background: #0154b9; color: #fff; }
    .btn-primary:hover { background: #1976d2; }
    .btn-warning { background: #ffe082; color: #b26a00; }
    .btn-warning:hover { background: #ffd54f; }
    .btn-danger { background: #e53935; color: #fff; }
    .btn-danger:hover { background: #b71c1c; }
    .alert-success {
        background: #e0fce0;
        color: #15803d;
        border: 1.5px solid #22c55e;
        border-radius: 6px;
        padding: 10px 16px;
        margin-bottom: 18px;
        font-weight: 600;
    }
    @media (max-width: 600px) {
        .location-table-container { padding: 10px 2px; }
        .table-location th, .table-location td { padding: 7px 4px; font-size: 13px; }
    }
</style>
<div class="location-table-container">
    <h2>Danh sách địa điểm</h2>
    <a href="{{ route('admin.locations.create') }}" class="btn btn-primary mb-3">+ Thêm địa điểm</a>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table-location">
        <thead>
            <tr>
                <th>#</th>
                <th>Tên địa điểm</th>
                <th>Địa chỉ</th>
                <th>Mô tả</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach($locations as $loc)
            <tr>
                <td>{{ $loc->id }}</td>
                <td>{{ $loc->name }}</td>
                <td>{{ $loc->address }}</td>
                <td>{{ $loc->description }}</td>
                <td>
                    <a href="{{ route('admin.locations.edit', $loc->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                    <form action="{{ route('admin.locations.destroy', $loc->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Xóa địa điểm này?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm">Xóa</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $locations->links() }}
</div>
@endsection