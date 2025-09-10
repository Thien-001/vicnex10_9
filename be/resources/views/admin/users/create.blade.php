@extends('layouts.layout')

@section('content')
<div class="head-title">
    <div class="left">
        <h1>Thêm tài khoản</h1>
        <ul class="breadcrumb">
            <li><a href="#">Tài khoản</a></li>
            <li><i class='bx bx-chevron-right'></i></li>
            <li><a class="active" href="#">Thêm tài khoản</a></li>
        </ul>
    </div>
    <a href="{{ route('admin.users.index') }}" class="btn-download">
            <span class="text">Quay lại</span>
        </a>
</div>

<div class="form-add">
    <h2>Thêm Người Dùng Mới</h2>

    @if ($errors->any())
        <div class="alert alert-danger" style="color: red; margin-bottom: 20px;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
    <label for="Role_ID">Quyền</label>
    <select id="Role_ID" name="Role_ID" required>
    <option value="">-- Chọn quyền --</option>
    @foreach($roles as $role)
        <option value="{{ $role->Role_ID }}">{{ $role->Name }}</option>
    @endforeach
</select>

</div>

        <div class="form-group">
            <label for="Name">Họ tên</label>
            <input type="text" id="Name" name="Name" required>
        </div>

        <div class="form-group">
            <label for="Email">Email</label>
            <input type="email" id="Email" name="Email" required>
        </div>

        <div class="form-group">
            <label for="Password">Mật khẩu</label>
            <input type="password" id="Password" name="Password" required>
        </div>

        <div class="form-group">
            <label for="Password_confirmation">Xác nhận mật khẩu</label>
            <input type="password" id="Password_confirmation" name="Password_confirmation" required>
        </div>

        <div class="form-group">
            <label for="Phone">Số điện thoại</label>
            <input type="text" id="Phone" name="Phone">
        </div>

        <div class="form-group">
            <label for="Gender">Giới tính</label>
            <select id="Gender" name="Gender">
                <option value="">-- Chọn --</option>
                <option value="male">Nam</option>
                <option value="female">Nữ</option>
                <option value="other">Khác</option>
            </select>
        </div>

        <div class="form-group">
            <label for="Date_of_birth">Ngày sinh</label>
            <input type="date" id="Date_of_birth" name="Date_of_birth">
        </div>

        <div class="form-group">
            <label for="Address">Địa chỉ</label>
            <textarea id="Address" name="Address" rows="3"></textarea>
        </div>
        <div class="form-group">
        <label for="Avatar">Ảnh đại diện</label>
        <input type="file" name="Avatar" id="Avatar" accept="image/*">
    </div>

        <div class="form-group">
            <label for="Status">Trạng thái</label>
            <select id="Status" name="Status">
                <option value="1">Kích hoạt</option>
                <option value="0">Tạm khóa</option>
            </select>
        </div>

        <div class="form-actions">
            <button type="submit">Tạo người dùng</button>
        </div>
    </form>
</div>
@endsection
