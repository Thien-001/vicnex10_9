@extends('layouts.layout')

@section('content')
<style>
    .body-content tbody tr td a {

    padding: 6px 12px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    color: #fff;
    transition: background-color 0.3s ease;
    margin: 0 4px;
}
</style>
<main>
<div class="head-title">
    <div class="left">
        <h1>Danh mục bài viết</h1>
        <ul class="breadcrumb">
            <li><a href="#">Danh mục bài viết</a></li>
        </ul>
    </div>
    <a href="{{ route('admin.post_categories.create') }}" class="btn-download">
        <span class="text">Thêm mới</span>
    </a>
</div>

    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<div class="body-content">
    <table>
        <thead>
            <tr>
                <th>STT</th>
                <th>Tên danh mục</th>
                <th>Slug</th>
                <th>Mô tả</th>
                <th>Ngày tạo</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
        @foreach($categories as $index => $category)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $category->Name }}</td>
                <td>{{ $category->Slug }}</td>
                <td>{{ $category->Description }}</td>
                <td>{{ $category->created_at->format('d/m/Y') }}</td>
                <td class="action-buttons">
                    <a href="{{ route('admin.post_categories.edit', $category->id) }}" class="admin-button-table">Sửa</a>
                    <form action="{{ route('admin.post_categories.destroy', $category->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="admin-button-table btn-delete" onclick="return confirm('Xóa danh mục này?')">Xóa</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $categories->links() }}
</div>
</main>
@endsection
