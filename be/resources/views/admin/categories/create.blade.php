@extends('layouts.layout')

@section('content')
<div class="head-title">
    <div class="left">
        <h1>Thêm danh mục</h1>
        <ul class="breadcrumb">
            <li><a href="#">Danh mục</a></li>
            <li><i class='bx bx-chevron-right'></i></li>
            <li><a class="active" href="#">Thêm danh mục</a></li>
        </ul>
    </div>

    <a href="{{ route('admin.categories.index') }}" class="btn-download">
		<span class="text">Quay lại</span>
	</a>
</div>

<div class="form-add">
    <h2>Thêm Danh Mục Mới</h2>
    <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="Name">Tên danh mục</label>
            <input type="text" id="Name" name="Name" required>
        </div>

        <div class="form-group">
            <label for="Image">Ảnh danh mục (không bắt buộc)</label>
            <input type="file" id="Image" name="Image" accept="image/*">
        </div>

        <div class="form-group">
            <label for="Description">Mô tả</label>
            <textarea id="Description" name="Description" rows="4"></textarea>
        </div>

        <div class="form-actions">
            <button type="submit">Thêm danh mục</button>
        </div>
    </form>
</div>
@endsection
