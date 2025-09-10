@extends('layouts.layout')

@section('content')
<main>
<div class="head-title">
    <div class="left">
        <h1>Sửa danh mục bài viết</h1>
        <ul class="breadcrumb">
            <li><a href="#">Danh mục bài viết</a></li>
            <li><i class='bx bx-chevron-right'></i></li>
            <li><a class="active" href="#">Sửa danh mục bài viết</a></li>
        </ul>
    </div>
    <a href="{{ route('admin.post_categories.index') }}" class="btn-download">
        <span class="text">Quay lại</span>
    </a>
</div>

<div class="form-add">
    <h2>Cập nhật Danh Mục</h2>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('admin.post_categories.update', $category->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Tên danh mục</label>
            <input type="text" id="name" name="Name" value="{{ old('Name', $category->Name) }}" required>
        </div>

        <div class="form-group">
            <label for="description">Mô tả</label>
            <textarea id="description" name="Description" rows="4">{{ old('Description', $category->Description) }}</textarea>
        </div>

        <div class="form-actions">
            <button type="submit">Cập nhật Danh Mục</button>
        </div>
    </form>
</div>
</main>
@endsection
