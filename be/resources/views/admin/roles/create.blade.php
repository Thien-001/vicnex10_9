@extends('layouts.layout')

@section('content')
<div class="head-title">
    <div class="left">
        <h1>Thêm vai trò</h1>
        <ul class="breadcrumb">
            <li><a href="#">Vai trò</a></li>
            <li><i class='bx bx-chevron-right'></i></li>
            <li><a class="active" href="#">Thêm vai trò</a></li>
        </ul>
    </div>
    <a href="{{ route('admin.roles.index') }}" class="btn-download">
        <span class="text">Quay lại</span>
    </a>
</div>

<div class="form-add">
    <h2>Thêm Vai Trò</h2>
    <form action="{{ route('admin.roles.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Tên vai trò:</label>
            <input type="text" name="Name" value="{{ old('Name') }}" required>
        </div>

        <div class="form-group">
            <label>Mô tả:</label>
            <textarea name="Description">{{ old('Description') }}</textarea>
        </div>

        <div class="form-actions">
            <button type="submit">Thêm vai trò</button>
        </div>
    </form>
</div>
@endsection
