@extends('layouts.layout')

@section('content')

    <div class="head-title">
        <div class="left">
            <h1>Bài viết</h1>
            <ul class="breadcrumb">
                <li><a href="#">Bài viết</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Danh sách bài viết</a></li>
            </ul>
        </div>
        <a href="{{ route('admin.posts.create') }}" class="btn-download">
            <span class="text">+ Thêm bài viết mới</span>
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success" style="margin: 15px 0;">{{ session('success') }}</div>
    @endif

    <div class="body-content">
        <table>
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Ảnh</th>
                    <th>Tiêu đề</th>
                    <th>Người viết</th>
                    <th>Danh mục</th>
                    <th>Nội dung</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($posts as $key => $post)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>
                            @if ($post->Thumbnail)
                                <img src="{{ asset($post->Thumbnail) }}" width="100" alt="Thumbnail">
                            @else
                                <span>Không có ảnh</span>
                            @endif
                        </td>
                        <td>{{ $post->Title }}</td>
                        <td>{{ $post->user->Name ?? 'Không rõ' }}</td>
                        <td>{{ $post->category->Name ?? 'Không có' }}</td>
                        <td>{{ Str::limit(strip_tags($post->Content), 50) }}</td>
                        <td>
                            <span style="color: {{ $post->Status ? '#28a745' : '#dc3545' }};">
                                {{ $post->Status ? 'Hiển thị' : 'Ẩn' }}
                            </span>
                        </td>
                        <td class="action-buttons">
                            <!-- Nút Sửa -->
                            <button class="admin-button-table btn-edit" style="margin-bottom: 5px;">
                                <a href="{{ route('admin.posts.edit', $post->Post_ID) }}" style="display:block; width:100%; height:100%; color:inherit; text-decoration:none;">Sửa</a>
                            </button>

                            <!-- Nút Xóa -->
                            <form action="{{ route('admin.posts.destroy', $post->Post_ID) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="admin-button-table btn-delete" onclick="return confirm('Bạn có chắc chắn muốn xóa bài viết này?')">Xóa</button>
                            </form>

                            <!-- Nút Xem -->
                            <button class="admin-button-table btn-view" style="margin-bottom: 5px;">
                                <a href="{{ route('admin.posts.show', $post->Post_ID) }}" style="display:block; width:100%; height:100%; color:inherit; text-decoration:none;">Xem</a>
                            </button>
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $posts->links() }}
    </div>
@endsection
