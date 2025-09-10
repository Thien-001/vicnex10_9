<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        return response()->json(Category::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'Name' => 'required|string|max:255',
        ]);

        $category = Category::create([
            'Name' => $request->Name,
            'Slug' => Str::slug($request->Name),
        ]);

        return response()->json($category, 201);
    }

    public function show($id)
    {
        $category = Category::findOrFail($id);
        return response()->json($category);
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $category->update([
            'Name' => $request->Name ?? $category->Name,
            'Slug' => Str::slug($request->Name ?? $category->Name),
        ]);

        return response()->json($category);
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return response()->json(['message' => 'Xoá thành công']);
    }
}
