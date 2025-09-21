@extends('layouts.layout')

@section('content')
<div class="head-title">
    <div class="left">
        <h1>Bình luận bài viết</h1>
        <ul class="breadcrumb">
            <li><a href="#">Bình luận</a></li>
            <li><i class='bx bx-chevron-right'></i></li>
            <li><a class="active" href="#">Bình luận bài viết</a></li>
        </ul>
    </div>
    <a href="#" class="btn-download">
        <i class='bx bxs-cloud-download'></i>
        <span class="text">Download PDF</span>
    </a>
</div>

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="body-content">
    <table>
        <thead>
            <tr>
                <th>STT</th>
                <th>Người dùng</th>
                <th>Nội dung</th>
                <th>Bài viết</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
        @foreach($comments as $index => $comment)
            <tr>
                <td>{{ $comments->firstItem() + $index }}</td>
                <td>{{ $comment->user->Name ?? 'Không xác định' }}</td>
                <td>{{ $comment->content }}</td>
                <td>
                    @if($comment->post)
                        {{ $comment->post->Title }}
                    @else
                        <span class="text-danger">Không có tiêu đề</span>
                    @endif
                </td>
                <td class="action-buttons">
                    <form action="{{ route('comments.destroy', $comment->ID) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="admin-button-table btn-delete" onclick="return confirm('Xóa bình luận này?')">Xóa</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $comments->links() }}
</div>
@endsection
