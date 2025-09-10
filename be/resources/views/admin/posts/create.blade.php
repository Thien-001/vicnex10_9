@extends('layouts.layout')

@section('content')
    <div class="head-title">
        <div class="left">
            <h1>Thêm Bài Viết</h1>
            <ul class="breadcrumb">
                <li><a href="#">Bài viết</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Thêm bài viết</a></li>
            </ul>
        </div>
        <a href="{{ route('admin.posts.index') }}" class="btn-download">
            <span class="text">Quay lại</span>
        </a>
    </div>

    {{-- Hiển thị lỗi --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-add">
        <h2>Thêm Bài Viết Mới</h2>
        <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="User_ID">Người đăng</label>
                <select name="User_ID" id="User_ID" required>
                    <option value="" disabled {{ old('User_ID') ? '' : 'selected' }}>Chọn người đăng</option>
                    @foreach($users as $user)
                        @if(isset($user->Role_ID) && $user->Role_ID == 1)
                            <option value="{{ $user->ID }}" {{ old('User_ID') == $user->ID ? 'selected' : '' }}>
                                {{ $user->Name }}
                            </option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="Category_ID">Danh mục</label>
                <select name="Category_ID" id="Category_ID" required>
                    <option value="" disabled {{ old('Category_ID') ? '' : 'selected' }}>Chọn danh mục</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('Category_ID') == $category->id ? 'selected' : '' }}>
                            {{ $category->Name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="Title">Tiêu đề</label>
                <input type="text" name="Title" id="Title" value="{{ old('Title') }}" required>
            </div>

            <div class="form-group">
                <label for="Thumbnail">Ảnh đại diện</label>
                <input type="file" name="Thumbnail" id="Thumbnail">
            </div>

            <div class="form-group">
                <label for="Content">Nội dung</label>
                <textarea name="Content" id="Content" rows="5" required>{{ old('Content') }}</textarea>
            </div>

            <div class="form-group">
                <label for="Excerpt">Trích đoạn</label>
                <textarea name="Excerpt" id="Excerpt" rows="3">{{ old('Excerpt') }}</textarea>
            </div>

            <div class="form-group">
                <label for="Status">Trạng thái</label>
                <select name="Status" id="Status">
                    <option value="1" {{ old('Status') == "1" ? 'selected' : '' }}>Hiển thị</option>
                    <option value="0" {{ old('Status') == "0" ? 'selected' : '' }}>Ẩn</option>
                </select>
            </div>

            <div class="form-actions">
                <button type="submit">Thêm bài viết</button>
            </div>
        </form>
    </div>
@endsection
