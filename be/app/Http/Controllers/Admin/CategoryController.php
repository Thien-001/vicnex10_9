<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    // Hiển thị danh sách danh mục
    public function index()
    {
        $categories = Category::paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    // Form tạo mới danh mục
    public function create()
    {
        return view('admin.categories.create');
    }

    // Lưu danh mục mới
    public function store(Request $request)
{
    $request->validate([
        'Name' => 'required|string|max:255',
        'Description' => 'nullable|string',
        'Image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $data = $request->only(['Name', 'Description']);

    if ($request->hasFile('Image')) {
        $image = $request->file('Image');
        $imageName = time() . '_' . $image->getClientOriginalName();
        $image->move(public_path('uploads/products'), $imageName);
        $data['Image'] = 'uploads/products/' . $imageName;
    }

    Category::create($data);

    return redirect()->route('admin.categories.index')->with('success', 'Thêm danh mục thành công!');
}


    // Form chỉnh sửa danh mục
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    // Cập nhật danh mục
    public function update(Request $request, $id)
{
    $category = Category::findOrFail($id);

    $request->validate([
        'Name' => 'required|string|max:255',
        'Description' => 'nullable|string',
        'Image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $data = $request->only(['Name', 'Description']);

    if ($request->hasFile('Image')) {
        // Xóa ảnh cũ nếu có
        if ($category->Image && file_exists(public_path($category->Image))) {
            unlink(public_path($category->Image));
        }

        $image = $request->file('Image');
        $imageName = time() . '_' . $image->getClientOriginalName();
        $image->move(public_path('uploads/products'), $imageName);
        $data['Image'] = 'uploads/products/' . $imageName;
    }

    $category->update($data);

    return redirect()->route('admin.categories.index')->with('success', 'Cập nhật danh mục thành công!');
}


    // Xóa danh mục
public function destroy($id)
{
    $category = Category::findOrFail($id);

    // Kiểm tra nếu danh mục có sản phẩm thì không cho xóa
    if ($category->products()->count() > 0) {
        return redirect()->route('admin.categories.index')
            ->with('error', 'Danh mục này đang có sản phẩm, không thể xóa!');
    }

    // Xóa ảnh nếu có
    if ($category->Image && file_exists(public_path($category->Image))) {
        unlink(public_path($category->Image));
    }

    $category->delete();

    return redirect()->route('admin.categories.index')
        ->with('success', 'Xoá danh mục thành công!');
}



}
