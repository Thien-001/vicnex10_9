@extends('layouts.layout')

@section('content')
<main>
<div class="head-title">
    <div class="left">
        <h1>Thêm danh mục bài viết</h1>
        <ul class="breadcrumb">
            <li><a href="#">Danh mục bài viết</a></li>
            <li><i class='bx bx-chevron-right'></i></li>
            <li><a class="active" href="#">Thêm danh mục bài viết</a></li>
        </ul>
    </div>
    <a href="{{ route('admin.post_categories.index') }}" class="btn-download">
        <span class="text">Quay lại</span>
    </a>
</div>

<div class="form-add">
    <h2>Thêm Danh Mục Mới</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.post_categories.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Tên danh mục</label>
            <input type="text" id="name" name="Name" value="{{ old('Name') }}" required>
        </div>

        <div class="form-group">
            <label for="description">Mô tả</label>
            <textarea id="description" name="Description" rows="4">{{ old('Description') }}</textarea>
        </div>

        <div class="form-actions">
            <button type="submit">Thêm Danh Mục</button>
        </div>
    </form>
</div>
</main>
@endsection
