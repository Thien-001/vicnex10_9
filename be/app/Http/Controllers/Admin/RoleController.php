<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
{
    $roles = Role::paginate(10);
    return view('admin.roles.index', compact('roles')); // ← truyền biến vào view
}

    public function create()
    {
        return view('admin.roles.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'Name' => 'required|string|max:255',
            'Description' => 'nullable|string|max:1000',
        ]);

        Role::create($validated);

        return redirect()->route('admin.roles.index')->with('success', 'Thêm vai trò thành công!');
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);
        return view('admin.roles.edit', compact('role'));
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $validated = $request->validate([
            'Name' => 'required|string|max:255',
            'Description' => 'nullable|string|max:1000',
        ]);

        $role->update($validated);

        return redirect()->route('admin.roles.index')->with('success', 'Cập nhật vai trò thành công!');
    }

    public function destroy($id)
{
    $role = Role::findOrFail($id);

    // Nếu role đã có user thì không cho xóa
    if ($role->users()->exists()) {
        return redirect()->route('admin.roles.index')
            ->with('error', 'Vai trò này đang được gán cho tài khoản, không thể xóa!');
    }

    $role->delete();

    return redirect()->route('admin.roles.index')->with('success', 'Xóa vai trò thành công!');
}

}
