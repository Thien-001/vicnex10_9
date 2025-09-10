@extends('layouts.layout')

@section('content')
<div class="head-title">
        <div class="left">
            <h1>Chỉnh sửa vai trò</h1>
            <ul class="breadcrumb">
                <li><a href="#">Vai trò</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Chỉnh sửa vai trò</a></li>
            </ul>
        </div>
        <a href="{{ route('admin.roles.index') }}" class="btn-download">
            <span class="text">Quay lại</span>
	</a>
    </div>

<div class="form-add">
<h2>Sửa Vai Trò</h2>
<form action="{{ route('admin.roles.update', $role->Role_ID) }}" method="POST">
    @csrf
    @method('PUT')
     <div class="form-group">
    <label>Tên vai trò:</label>
    <input type="text" name="Name" value="{{ $role->Name }}" required><br><br>
    </div>
     <div class="form-group">
    <label>Mô tả:</label>
    <textarea name="Description">{{ $role->Description }}</textarea><br><br>
    </div>
    <div class="form-actions">
    <button type="submit">Cập nhật</button>
    </div>
</form>
</div>
@endsection
