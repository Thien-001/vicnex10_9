<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PostCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostCategoryController extends Controller
{
    public function index()
    {
        $categories = PostCategory::paginate(10);
        return view('admin.post_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.post_categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'Name' => 'required|string|max:255',
            'Description' => 'nullable|string',
        ]);
        $validated['Slug'] = Str::slug($validated['Name']);
        PostCategory::create($validated);
        return redirect()->route('admin.post_categories.index')->with('success', 'Tạo danh mục thành công!');
    }

    public function show($id)
    {
        $category = PostCategory::findOrFail($id);
        return view('admin.post_categories.show', compact('category'));
    }

    public function edit($id)
    {
        $category = PostCategory::findOrFail($id);
        return view('admin.post_categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = PostCategory::findOrFail($id);
        $validated = $request->validate([
            'Name' => 'required|string|max:255',
            'Description' => 'nullable|string',
        ]);
        $validated['Slug'] = Str::slug($validated['Name']);
        $category->update($validated);
        return redirect()->route('admin.post_categories.index')->with('success', 'Cập nhật danh mục thành công!');
    }

    public function destroy($id)
{
    $category = PostCategory::findOrFail($id);

    if ($category->posts()->exists()) {
        return redirect()->route('admin.post_categories.index')
            ->with('error', 'Danh mục đang có bài viết, không thể xóa!');
    }

    $category->delete();

    return redirect()->route('admin.post_categories.index')->with('success', 'Xóa danh mục thành công!');
}

}
