@extends('layouts.layout')

@section('content')
<style>
.admin-filter-form {
        background: #f8f8f8;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    }

    .admin-filter-form label {
        display: block;
        font-weight: 600;
        margin-bottom: 5px;
        font-size: 14px;
    }

    .admin-filter-form input,
    .admin-filter-form select {
        padding: 8px 12px;
        font-size: 14px;
        border-radius: 6px;
        border: 1px solid #ccc;
        width: 200px;
    }

</style>
<div class="head-title">
    <div class="left">
        <h1>Tài khoản</h1>
        <ul class="breadcrumb">
            <li><a href="#">Tài khoản</a></li>
            <li><i class='bx bx-chevron-right'></i></li>
            <li><a class="active" href="#">Danh sách tài khoản</a></li>
        </ul>
    </div>
    <a href="{{ route('admin.users.create') }}" class="btn-download">
        <span class="text">+ Thêm tài khoản mới</span>
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
    {{-- Bộ lọc --}}
    <form action="{{ route('admin.users.index') }}" method="GET" style="margin-bottom: 20px;" class="admin-filter-form">
        <div style="display: flex; flex-wrap: wrap; gap: 15px; align-items: flex-end;">
            <div>
                <label for="keyword">Tìm kiếm:</label>
                <input type="text" name="keyword" id="keyword" value="{{ request('keyword') }}">
            </div>

            <div>
                <label for="role">Vai trò:</label>
                <select name="role" id="role">
                    <option value="">Tất cả</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->Role_ID }}" {{ request('role') == $role->Role_ID ? 'selected' : '' }}>
                            {{ $role->Name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="status">Trạng thái:</label>
                <select name="status" id="status">
                    <option value="">Tất cả</option>
                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Kích hoạt</option>
                    <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Tạm khóa</option>
                </select>
            </div>

            <div>
                        <div>
            <button type="submit" class="admin-form-loc">Lọc</button>
            <button type="submit" class="admin-form-loc"><a href="{{ route('admin.users.index') }}">Đặt lại</a></button>
        </div>
            </div>
        </div>
    </form>

    <table>
        <thead>
            <tr>
                <th>STT</th>
                <th>Ảnh</th>
                <th>Tên</th>
                <th>Email</th>
                <th>SĐT</th>
                <th>Vai trò</th>
                <th>Thao Tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $index => $user)
                <tr>
                    <td>{{ $index + 1 + ($users->currentPage() - 1) * $users->perPage() }}</td>
                    <td>
                        @php
                            $avatar = $user->Avatar
                                ? (filter_var($user->Avatar, FILTER_VALIDATE_URL)
                                    ? $user->Avatar
                                    : asset($user->Avatar))
                                : ($user->Gender == 'female'
                                    ? asset('WebAdmin/img/avt/default-avatar-female.png')
                                    : asset('WebAdmin/img/avt/default-avatar-male.png'));
                        @endphp
                        <img src="{{ $avatar }}" width="60" height="60" alt="avatar" style="object-fit:cover; border-radius:50%;">
                    </td>
                    <td>{{ $user->Name }}</td>
                    <td>{{ $user->Email }}</td>
                    <td>{{ $user->Phone }}</td>
                    <td>{{ $user->role->Name ?? 'Không rõ' }}</td>
                    <td class="action-buttons">
                        <!-- Nút Sửa -->
                        <button class="admin-button-table">
                            <a href="{{ route('admin.users.edit', $user->ID) }}" style="display:block; width:100%; height:100%; color:inherit; text-decoration:none;">Sửa</a>
                        </button>

                        <!-- Nút Xoá -->
                        <form action="{{ route('admin.users.destroy', $user->ID) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="admin-button-table btn-delete" onclick="return confirm('Bạn chắc chắn muốn xóa?')">Xoá</button>
                        </form>
                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 20px">
        {{ $users->appends(request()->query())->links() }}
    </div>
    {{ $users->links() }}
</div>
@endsection
