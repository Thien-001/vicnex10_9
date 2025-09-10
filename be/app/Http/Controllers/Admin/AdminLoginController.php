<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // Chỉ cho phép đăng nhập với Role_ID là 1 (admin) hoặc 2 (nhân viên)
        $credentials['Role_ID'] = [1, 2];

        // Sử dụng where để kiểm tra Role_ID
        $user = \App\Models\User::where('email', $credentials['email'])
            ->whereIn('Role_ID', $credentials['Role_ID'])
            ->first();

        if ($user && Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']])) {
            // Đăng nhập thành công
            return redirect()->route('admin.dashboard');
        }

        // Đăng nhập thất bại
        return redirect()->route('admin.login')->with('error', 'Email hoặc mật khẩu không đúng, hoặc bạn không có quyền truy cập!');
    }
}