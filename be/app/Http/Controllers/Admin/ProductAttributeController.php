<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductAttribute;
use Illuminate\Http\Request;

class ProductAttributeController extends Controller
{
    public function index()
    {
        $attributes = ProductAttribute::paginate(10);
        return view('admin.product_attributes.index', compact('attributes'));
    }

    public function create()
    {
        return view('admin.product_attributes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'Name' => 'required|string|max:255',
            'Description' => 'nullable|string|max:1000',
        ]);

        $validated['Create_at'] = now();

        ProductAttribute::create($validated);

        return redirect()->route('admin.product_attributes.index')
            ->with('success', 'Thêm thuộc tính thành công!');
    }

    public function edit($id)
    {
        $attribute = ProductAttribute::findOrFail($id);
        return view('admin.product_attributes.edit', compact('attribute'));
    }

    public function update(Request $request, $id)
    {
        $attribute = ProductAttribute::findOrFail($id);

        $validated = $request->validate([
            'Name' => 'required|string|max:255',
            'Description' => 'nullable|string|max:1000',
        ]);

        $validated['Update_at'] = now();

        $attribute->update($validated);

        return redirect()->route('admin.product_attributes.index')
            ->with('success', 'Cập nhật thuộc tính thành công!');
    }

    public function destroy($id)
    {
        $attribute = ProductAttribute::findOrFail($id);
        $attribute->delete();

        return redirect()->route('admin.product_attributes.index')
            ->with('success', 'Xóa thuộc tính thành công!');
    }
}
