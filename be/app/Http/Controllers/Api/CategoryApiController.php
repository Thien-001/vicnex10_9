<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category; // ✅ BẠT BUỘC PHẢI CÓ DÒNG NÀY

class CategoryApiController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('slug')) {
            $category = Category::where('Slug', $request->get('slug'))->first();
            return response()->json($category);
        }
        $categories = Category::all();
        return response()->json($categories);
    }
}
