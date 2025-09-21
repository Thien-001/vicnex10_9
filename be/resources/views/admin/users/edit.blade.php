@php
    // Giả sử Role_ID của User thường là 3 (bạn đổi đúng theo DB của bạn)
    $isUserRole = $user->Role_ID == \App\Models\Role::USER;
@endphp

@extends('layouts.layout')

@section('content')
<div class="head-title">
    <div class="left">
        <h1>Chỉnh sửa tài khoản</h1>
        <ul class="breadcrumb">
            <li><a href="#">Tài khoản</a></li>
            <li><i class='bx bx-chevron-right'></i></li>
            <li><a class="active" href="#">Chỉnh sửa tài khoản</a></li>
        </ul>
    </div>
    <a href="{{ route('admin.users.index') }}" class="btn-download">
        <span class="text">Quay lại</span>
    </a>
</div>

<div class="form-add">
    <h2>Chỉnh sửa Người Dùng</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li style="color: red">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.users.update', $user) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="Role_ID">Quyền</label>
            <select id="Role_ID" name="Role_ID" {{ $isUserRole ? 'disabled' : '' }}>
                <option value="">-- Chọn quyền --</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->Role_ID }}" {{ $user->Role_ID == $role->Role_ID ? 'selected' : '' }}>
                        {{ $role->Name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="Name">Họ tên</label>
            <input type="text" id="Name" name="Name" value="{{ old('Name', $user->Name) }}" {{ $isUserRole ? 'readonly' : '' }}>
        </div>

        <div class="form-group">
            <label for="Email">Email</label>
            <input type="email" id="Email" name="Email" value="{{ old('Email', $user->Email) }}" {{ $isUserRole ? 'readonly' : '' }}>
        </div>

        @unless($isUserRole)
            <div class="form-group">
                <label for="Password">Mật khẩu mới (nếu muốn đổi)</label>
                <input type="password" id="Password" name="Password">
            </div>

            <div class="form-group">
                <label for="Password_confirmation">Xác nhận mật khẩu</label>
                <input type="password" id="Password_confirmation" name="Password_confirmation">
            </div>
        @endunless

        <div class="form-group">
            <label for="Phone">Số điện thoại</label>
            <input type="text" id="Phone" name="Phone" value="{{ old('Phone', $user->Phone) }}" {{ $isUserRole ? 'readonly' : '' }}>
        </div>

        <div class="form-group">
            <label for="Gender">Giới tính</label>
            <select id="Gender" name="Gender" {{ $isUserRole ? 'disabled' : '' }}>
                <option value="">-- Chọn --</option>
                <option value="male" {{ $user->Gender == 'male' ? 'selected' : '' }}>Nam</option>
                <option value="female" {{ $user->Gender == 'female' ? 'selected' : '' }}>Nữ</option>
                <option value="other" {{ $user->Gender == 'other' ? 'selected' : '' }}>Khác</option>
            </select>
        </div>

        <div class="form-group">
            <label for="Date_of_birth">Ngày sinh</label>
            <input type="date" id="Date_of_birth" name="Date_of_birth" value="{{ old('Date_of_birth', $user->Date_of_birth) }}" {{ $isUserRole ? 'readonly' : '' }}>
        </div>

        <div class="form-group">
            <label for="Address">Địa chỉ</label>
            <textarea id="Address" name="Address" rows="3" {{ $isUserRole ? 'readonly' : '' }}>{{ old('Address', $user->Address) }}</textarea>
        </div>

        <div class="form-group">
            <label for="Status">Trạng thái</label>
            <select id="Status" name="Status">
                <option value="1" {{ $user->Status == 1 ? 'selected' : '' }}>Kích hoạt</option>
                <option value="0" {{ $user->Status == 0 ? 'selected' : '' }}>Tạm khóa</option>
            </select>
        </div>

        <div class="form-actions">
            <button type="submit">Cập nhật</button>
        </div>
    </form>
</div>
@endsection
