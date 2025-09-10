<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class RolesApiController extends Controller
{
    // Lấy danh sách tất cả role
    public function index()
    {
        return response()->json(Role::all(), 200);
    }

    // Tạo mới role
    public function store(Request $request)
    {
        $validated = $request->validate([
            'Name' => 'required|string|max:255',
            'Description' => 'nullable|string',
        ]);

        $role = Role::create($validated);
        return response()->json($role, 201);
    }

    // Lấy 1 role theo ID
    public function show($id)
    {
        $role = Role::find($id);
        if (!$role) {
            return response()->json(['message' => 'Không tìm thấy vai trò'], 404);
        }
        return response()->json($role, 200);
    }

    // Cập nhật role
    public function update(Request $request, $id)
    {
        $role = Role::find($id);
        if (!$role) {
            return response()->json(['message' => 'Không tìm thấy vai trò'], 404);
        }

        $validated = $request->validate([
            'Name' => 'required|string|max:255',
            'Description' => 'nullable|string',
        ]);

        $role->update($validated);
        return response()->json($role, 200);
    }

    // Xóa role
    public function destroy($id)
    {
        $role = Role::find($id);
        if (!$role) {
            return response()->json(['message' => 'Không tìm thấy vai trò'], 404);
        }

        $role->delete();
        return response()->json(['message' => 'Xóa vai trò thành công'], 200);
    }
}
