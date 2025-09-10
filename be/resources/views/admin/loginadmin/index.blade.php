<!-- @extends('layouts.layout') -->

@section('content')
<style>
.login-admin-container {
    max-width: 400px;
    margin: 60px auto;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 12px rgba(1,84,185,0.07);
    padding: 32px 28px;
}
.login-admin-title {
    text-align: center;
    color: #0154b9;
    font-weight: 700;
    font-size: 2rem;
    margin-bottom: 24px;
}
.login-admin-form label {
    font-weight: 500;
    color: #0154b9;
    margin-bottom: 8px;
    display: block;
}
.login-admin-form input {
    width: 100%;
    padding: 10px 12px;
    border: 1.5px solid #e3f0ff;
    border-radius: 8px;
    margin-bottom: 18px;
    font-size: 15px;
}
.login-admin-form button {
    width: 100%;
    background: #0154b9;
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 10px 0;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s;
}
.login-admin-form button:hover {
    background: #013e8a;
}
.login-admin-error {
    color: #e74c3c;
    text-align: center;
    margin-bottom: 12px;
    font-size: 15px;
}
</style>

<div class="login-admin-container">
    <div class="login-admin-title">Đăng nhập Admin</div>
    @if(session('error'))
        <div class="login-admin-error">{{ session('error') }}</div>
    @endif
    <form class="login-admin-form" method="POST" action="{{ route('admin.login.submit') }}">
        @csrf
        <label for="email">Email</label>
        <input type="email" name="email" id="email" required autofocus>

        <label for="password">Mật khẩu</label>
        <input type="password" name="password" id="password" required>

        <button type="submit">Đăng nhập</button>
    </form>
</div>
@endsection