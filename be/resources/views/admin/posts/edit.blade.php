@extends('layouts.layout')

@section('content')
<main>
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
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-add">
        <h2>Thêm Bài Viết Mới</h2>
        <form action="{{ route('admin.posts.update', $post->Post_ID) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')


                <div class="form-group">
                    <label for="User_ID">Người viết</label>
                    <select name="User_ID" id="User_ID" required>
                        @foreach ($users as $user)
                            @if(isset($user->Role_ID) && $user->Role_ID == 1)
                                <option value="{{ $user->ID }}" {{ (isset($post) && $post->User_ID == $user->ID) ? 'selected' : '' }}>
                                    {{ $user->Name }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="Category_ID">Danh mục</label>
                    <select name="Category_ID" id="Category_ID" required>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ $post->Category_ID == $cat->id ? 'selected' : '' }}>
                                {{ $cat->Name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="Title">Tiêu đề</label>
                    <input type="text" name="Title" id="Title" value="{{ old('Title', $post->Title) }}" required>
                </div>

            <div class="form-group">
                    <label for="Thumbnail">Ảnh hiện tại:</label><br>
                    @if ($post->Thumbnail)
                        <img src="{{ asset($post->Thumbnail) }}" width="100" style="margin-bottom: 10px;" alt="Ảnh hiện tại">
                    @else
                        <span>Không có ảnh</span>
                    @endif
                </div>



                <div class="form-group">
                    <label for="Thumbnail_upload">Hoặc chọn file ảnh mới</label>
                    <input type="file" name="Thumbnail_upload" id="Thumbnail_upload" class="form-control-file">
                </div>


                <div class="form-group">
                    <label for="Content">Nội dung</label>
                    <textarea name="Content" id="Content" rows="6" required>{{ old('Content', $post->Content) }}</textarea>
                </div>

                <div class="form-group">
                    <label for="Excerpt">Tóm tắt</label>
                    <textarea name="Excerpt" id="Excerpt" rows="3">{{ old('Excerpt', $post->Excerpt) }}</textarea>
                </div>

                <div class="form-group">
                    <label for="Status">Trạng thái</label>
                    <select name="Status" id="Status" required>
                        <option value="1" {{ $post->Status == 1 ? 'selected' : '' }}>Hiển thị</option>
                        <option value="0" {{ $post->Status == 0 ? 'selected' : '' }}>Ẩn</option>
                    </select>
                </div>

            <div class="form-actions">
                <button type="submit">Cập nhật</button>
            </div>
        </form>
    </div>
</main>
@endsection
