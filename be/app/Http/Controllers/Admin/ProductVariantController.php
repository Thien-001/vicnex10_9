<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductVariant;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductVariantController extends Controller
{
    public function index()
    {
        $variants = ProductVariant::with('product')->paginate(10);
        return view('admin.variants.index', compact('variants'));
    }

    public function create()
    {
        $products = Product::all();
        return view('admin.variants.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'Product_ID'      => 'required|exists:products,Product_ID',
            'SKU'             => 'nullable|string|max:100',
            'Variant_name'    => 'required|string|max:255',
            'Price'           => 'required|numeric|min:0',
            'Discount_price'  => 'nullable|numeric|min:0|lt:Price',
            'Quantity'        => 'required|integer|min:0',
            'Image'           => 'nullable|image|max:2048',
            'Status'          => 'boolean',
        ]);

        if ($request->hasFile('Image')) {
            $path = $request->file('Image')->store('uploads/variants', 'public');
            $validated['Image'] = 'storage/' . $path;
        }

        $validated['Status'] = $request->has('Status') ? $request->Status : 0;
        $validated['Created_at'] = now();

        ProductVariant::create($validated);

        return redirect()->route('admin.variants.index')->with('success', 'Thêm biến thể thành công!');
    }

    public function edit($id)
    {
        $variant = ProductVariant::findOrFail($id);
        $products = Product::all();
        return view('admin.variants.edit', compact('variant', 'products'));
    }

    public function update(Request $request, $id)
    {
        $variant = ProductVariant::findOrFail($id);

        $validated = $request->validate([
            'Product_ID'      => 'required|exists:products,Product_ID',
            'SKU'             => 'nullable|string|max:100',
            'Variant_name'    => 'required|string|max:255',
            'Price'           => 'required|numeric|min:0',
            'Discount_price'  => 'nullable|numeric|min:0|lt:Price',
            'Quantity'        => 'required|integer|min:0',
            'Image'           => 'nullable|image|max:2048',
            'Status'          => 'boolean',
        ]);

        if ($request->hasFile('Image')) {
            $path = $request->file('Image')->store('uploads/variants', 'public');
            $validated['Image'] = 'storage/' . $path;
        } else {
            $validated['Image'] = $variant->Image;
        }

        $validated['Status'] = $request->has('Status') ? $request->Status : 0;
        $validated['Updated_at'] = now();

        $variant->update($validated);

        return redirect()->route('admin.variants.index')->with('success', 'Cập nhật biến thể thành công!');
    }

    public function destroy($id)
    {
        $variant = ProductVariant::findOrFail($id);
        $variant->delete();

        return redirect()->route('admin.variants.index')->with('success', 'Xóa biến thể thành công!');
    }
}
