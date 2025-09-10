@extends('layouts.layout')

@section('content')
<main>
    <div class="head-title">
        <div class="left">
            <h1>Chi Tiết Bài Viết</h1>
            <ul class="breadcrumb">
                <li><a href="#">Bài viết</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Chi tiết</a></li>
            </ul>
        </div>
        <a href="{{ route('admin.posts.index') }}" class="btn-download">
            <span class="text">Quay lại</span>
        </a>
    </div>

    <div style="
        padding: 36px 32px;
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 4px 24px rgba(1,84,185,0.10);
        max-width: 900px;
        margin: 32px auto 0;
        font-size: 17px;
    ">
        @if($post->Thumbnail)
            <div style="text-align: center; margin-bottom: 28px;">
                <img src="{{ asset($post->Thumbnail) }}" alt="Thumbnail" style="max-width: 100%; height: auto; border-radius: 14px; box-shadow: 0 2px 12px rgba(1,84,185,0.08);">
            </div>
        @endif

        <h2 style="margin-bottom: 12px; font-size: 28px; font-weight: 800; color: #0154b9;">{{ $post->Title }}</h2>

        <div style="display: flex; flex-wrap: wrap; gap: 28px; margin-bottom: 18px;">
            <div><strong>Người đăng:</strong> {{ $post->user->Name ?? 'N/A' }}</div>
            <div><strong>Danh mục:</strong> {{ $post->category->Name ?? 'N/A' }}</div>
            <div>
                <strong>Trạng thái:</strong>
                <span style="color: {{ $post->Status ? '#1bbf4c' : '#e74c3c' }}; font-weight: 600;">
                    {{ $post->Status ? 'Hiển thị' : 'Ẩn' }}
                </span>
            </div>
            <div style="display: flex; align-items: center;">
                <strong style="margin-right: 6px;">Lượt xem:</strong>
                <span style="color: #0154b9; font-weight: 700;">
                    <i class="bx bx-show" style="margin-right: 4px;"></i>{{ $post->View ?? 0 }}
                </span>
            </div>
        </div>

        @if($post->Excerpt)
            <div style="margin-top: 18px;">
                <strong>Trích đoạn:</strong>
                <div style="background-color: #f6f8fc; padding: 14px 18px; border-radius: 8px; margin-top: 6px; color: #444;">
                    {{ $post->Excerpt }}
                </div>
            </div>
        @endif

        <div style="margin-top: 28px;">
            <strong>Nội dung:</strong>
            <div style="padding: 18px 20px; border: 1.5px solid #e3eaf5; border-radius: 10px; margin-top: 8px; background: #fafdff;">
                {!! $post->Content !!}
            </div>
        </div>

        <div style="margin-top: 36px; display: flex; gap: 16px;">
            <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">Quay lại danh sách</a>
            <a href="{{ route('admin.posts.edit', $post->Post_ID) }}" class="btn btn-primary">Chỉnh sửa</a>
        </div>
    </div>
</main>
@endsection
