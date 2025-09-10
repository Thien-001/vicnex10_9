@extends('layouts.layout')

@section('title', 'Chỉnh sửa danh mục')

@section('content')
<div class="head-title">
	<div class="left">
		<h1>Danh mục sản phẩm</h1>
		<ul class="breadcrumb">
			<li><a href="#">Danh mục sản phẩm</a></li>
			<li><i class='bx bx-chevron-right'></i></li>
			<li><a class="active" href="#">Chỉnh sửa danh mục</a></li>
		</ul>
	</div>
	<a href="{{ route('admin.categories.index') }}" class="btn-download">
		<span class="text">Quay lại</span>
	</a>
</div>



<div class="form-add">
    <h2>Thêm Danh Mục Mới</h2>
<form action="{{ route('admin.categories.update', $category->Categories_ID) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="Name">Tên danh mục</label>
            <input type="text" id="Name" name="Name" value="{{ old('Name', $category->Name) }}" required>
        </div>

        <div class="form-group">
            <label for="Image">Ảnh danh mục</label>
            <input type="file" id="Image" name="Image" accept="image/*">
            @if($category->Image)
                <img src="{{ asset($category->Image) }}" alt="Ảnh hiện tại" width="100">
            @endif

        </div>

        <div class="form-group">
            <label for="Description">Mô tả</label>
             <textarea id="Description" name="Description">{{ old('Description', $category->Description) }}</textarea>
        </div>

        <div class="form-actions">
            <button type="submit">Cập nhật danh mục</button>
        </div>
    </form>
</div>
@endsection
