<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // Hiển thị danh sách user
    public function index(Request $request)
    {
        $query = User::with('role');

        if ($request->filled('keyword')) {
            $query->where(function ($q) use ($request) {
                $q->where('Name', 'like', '%' . $request->keyword . '%')
                  ->orWhere('Email', 'like', '%' . $request->keyword . '%');
            });
        }

        if ($request->filled('role')) {
            $query->where('Role_ID', $request->role);
        }

        if ($request->filled('status')) {
            $query->where('Status', $request->status);
        }

        $users = $query->paginate(10)->appends($request->query());
        $roles = Role::all();

        return view('admin.users.index', compact('users', 'roles'));
    }

    // Hiển thị form tạo user
    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    // Lưu user mới vào DB
    public function store(Request $request)
    {
        $validated = $request->validate([
            'Name'          => 'required|string|max:255',
            'Email'         => 'required|email|unique:user,Email',
            'Password'      => 'required|string|min:6|confirmed',
            'Phone'         => 'nullable|string|max:20',
            'Gender'        => 'nullable|in:male,female,other',
            'Date_of_birth' => 'nullable|date',
            'Status'        => 'nullable|boolean',
            'Address'       => 'nullable|string|max:500',
            'ward'          => 'nullable|string|max:100',
            'district'      => 'nullable|string|max:100',
            'province'      => 'nullable|string|max:100',
            'Role_ID'       => 'required|exists:roles,Role_ID',
            'Avatar'        => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = new User();
        $user->fill($validated);
        $user->Password = bcrypt($validated['Password']);
        $user->Status = $request->Status ?? 0;

        // Xử lý ảnh upload
        if ($request->hasFile('Avatar')) {
            $file = $request->file('Avatar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/users'), $filename);
            $user->Avatar = url('uploads/users/' . $filename);
        }

        $user->Created_at = now();
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Tạo user thành công!');
    }

    // Hiển thị form chỉnh sửa
    public function edit($id)
    {
        $user  = User::findOrFail($id);
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    // Cập nhật user đã có
public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    // Nếu là khách hàng => chỉ cho phép update Status
    if ($user->Role_ID == \App\Models\Role::USER) {
        $validated = $request->validate([
            'Status' => 'required|boolean',
        ]);

        $user->Status = $validated['Status'];
        $user->Updated_at = now();
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Cập nhật trạng thái thành công!');
    }

    // Nếu là Admin/Staff => update đầy đủ
    $validated = $request->validate([
        'Name'          => 'required|string|max:255',
        'Email'         => [
            'required',
            'email',
            Rule::unique('user', 'Email')->ignore($user->ID, 'ID'),
        ],
        'Password'      => 'nullable|string|min:6|confirmed',
        'Phone'         => 'nullable|string|max:20',
        'Gender'        => 'nullable|in:male,female,other',
        'Date_of_birth' => 'nullable|date',
        'Status'        => 'nullable|boolean',
        'Address'       => 'nullable|string|max:500',
        'ward'          => 'nullable|string|max:100',
        'district'      => 'nullable|string|max:100',
        'province'      => 'nullable|string|max:100',
        'Role_ID'       => 'required|exists:roles,Role_ID',
    ]);

    $data = $validated;
    unset($data['Password']);

    $user->fill($data);

    if ($request->filled('Password')) {
        $user->Password = bcrypt($request->Password);
    }

    $user->Status = $request->Status ?? 0;
    $user->Updated_at = now();

    // Xử lý ảnh upload
    if ($request->hasFile('Avatar')) {
        $file = $request->file('Avatar');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads/users'), $filename);
        $user->Avatar = url('uploads/users/' . $filename);
    }

    $user->save();

    return redirect()->route('admin.users.index')->with('success', 'Cập nhật user thành công!');
}



    // Xóa user khỏi hệ thống
// Xoá user
public function destroy($id)
{
    $user = User::findOrFail($id);

    if ($user->Role_ID == Role::USER) {
        return redirect()->route('admin.users.index')
            ->with('error', 'Không thể xóa tài khoản khách hàng, chỉ được cập nhật trạng thái!');
    }

    if ($user->Role_ID == Role::ADMIN) {
        return redirect()->route('admin.users.index')
            ->with('error', 'Không thể xóa tài khoản admin!');
    }

    // Nhân viên thì được phép xoá
    $user->delete();

    return redirect()->route('admin.users.index')->with('success', 'Xóa user thành công!');
}


    // Cập nhật profile cá nhân
    public function updateProfile(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $user->Name = $request->input('Name');
        $user->Phone = $request->input('Phone');
        $user->Gender = $request->input('Gender');
        $user->Date_of_birth = $request->input('Date_of_birth');
        $user->Address = $request->input('Address');
        $user->ward = $request->input('ward');
        $user->district = $request->input('district');
        $user->province = $request->input('province');

        // Xử lý ảnh đại diện nếu có
        if ($request->hasFile('Avatar')) {
            if ($user->Avatar && file_exists(public_path($user->Avatar))) {
                @unlink(public_path($user->Avatar));
            }
            $file = $request->file('Avatar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/users'), $filename);
            $user->Avatar = url('uploads/users/' . $filename);
        }

        $user->save();

        return response()->json(['message' => 'Cập nhật thành công!', 'user' => $user]);
    }
}
