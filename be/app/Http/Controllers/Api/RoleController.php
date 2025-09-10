<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class RolesApiController extends Controller
{
    // GET /api/roles
    public function index()
    {
        return response()->json(Role::all(), 200);
    }

    // (Có thể không cần các method dưới nếu chỉ muốn GET)
    public function show($id)
    {
        $role = Role::find($id);
        if (! $role) {
            return response()->json(['message' => 'Role not found'], 404);
        }
        return response()->json($role, 200);
    }
}
