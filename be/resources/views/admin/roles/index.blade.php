@extends('layouts.layout')

@section('content')
<div class="head-title">
        <div class="left">
            <h1>Danh sách vai trò</h1>
            <ul class="breadcrumb">
                <li><a href="#">Vai trò</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Danh sách vai trò</a></li>
            </ul>
        </div>
        <a href="{{ route('admin.roles.create') }}" class="btn-download">
		<span class="text">+ Thêm vai trò mới</span>
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
    <h1>Danh sách Vai Trò</h1>


    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên vai trò</th>
                <th>Mô tả</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($roles as $role)
                <tr>
                    <td>{{ $role->Role_ID }}</td>
                    <td>{{ $role->Name }}</td>
                    <td>{{ $role->Description }}</td>
                    <td class="action-buttons">
                        <!-- Nút Sửa -->
                        <button class="admin-button-table">
                            <a href="{{ route('admin.roles.edit', $role->Role_ID) }}" style="display:block; width:100%; height:100%; color:inherit; text-decoration:none;">Sửa</a>
                        </button>

                        <!-- Nút Xoá -->
                        <form action="{{ route('admin.roles.destroy', $role->Role_ID) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="admin-button-table btn-delete" onclick="return confirm('Xóa vai trò này?')">Xoá</button>
                        </form>
                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $roles->links() }}
    </div>
@endsection
