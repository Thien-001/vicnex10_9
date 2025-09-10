<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PostCategory;

class PostCategoryApiController extends Controller
{
    public function index()
    {
        // Chỉ trả về các trường cần thiết, đúng với cấu trúc bảng post_categories
        return response()->json(
            PostCategory::select('id', 'Name', 'Slug', 'Description', 'created_at', 'updated_at')->get(),
            200
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'Name' => 'required|string|max:255',
            'Slug' => 'required|string|max:255|unique:post_categories,Slug',
            'Description' => 'nullable|string',
        ]);

        $category = PostCategory::create($validated);
        return response()->json($category, 201);
    }

    public function show($id)
    {
        $category = PostCategory::find($id);
        if (!$category) {
            return response()->json(['message' => 'Not found'], 404);
        }
        return response()->json($category, 200);
    }

    public function update(Request $request, $id)
    {
        $category = PostCategory::find($id);
        if (!$category) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $validated = $request->validate([
            'Name' => 'required|string|max:255',
            'Slug' => 'required|string|max:255|unique:post_categories,Slug,' . $id,
            'Description' => 'nullable|string',
        ]);

        $validated['updated_at'] = now();

        $category->update($validated);
        return response()->json($category, 200);
    }

    public function destroy($id)
    {
        $category = PostCategory::find($id);
        if (!$category) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $category->delete();
        return response()->json(['message' => 'Deleted'], 200);
    }
}
