<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductVariantValue;
use App\Models\ProductVariant;
use App\Models\ProductValue;
use Illuminate\Http\Request;

class ProductVariantValueController extends Controller
{
    public function index()
    {
        $variantValues = ProductVariantValue::with(['variant', 'value'])->paginate(10);
        return view('admin.product_variant_values.index', compact('variantValues'));
    }

    public function create()
    {
        $variants = ProductVariant::all();
        $values = ProductValue::all();
        return view('admin.product_variant_values.create', compact('variants', 'values'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'Variant_ID' => 'required|exists:product_variants,Variant_ID',
            'Values_ID' => 'required|exists:product_values,Values_ID',
        ]);

        ProductVariantValue::create($validated);

        return redirect()->route('admin.product_variant_values.index')
            ->with('success', 'Thêm giá trị biến thể thành công!');
    }

    public function edit($id)
    {
        $variantValue = ProductVariantValue::findOrFail($id);
        $variants = ProductVariant::all();
        $values = ProductValue::all();
        return view('admin.product_variant_values.edit', compact('variantValue', 'variants', 'values'));
    }

    public function update(Request $request, $id)
    {
        $variantValue = ProductVariantValue::findOrFail($id);

        $validated = $request->validate([
            'Variant_ID' => 'required|exists:product_variants,Variant_ID',
            'Values_ID' => 'required|exists:product_values,Values_ID',
        ]);

        $variantValue->update($validated);

        return redirect()->route('admin.product_variant_values.index')
            ->with('success', 'Cập nhật thành công!');
    }

    public function destroy($id)
    {
        $variantValue = ProductVariantValue::findOrFail($id);
        $variantValue->delete();

        return redirect()->route('admin.product_variant_values.index')
            ->with('success', 'Xóa thành công!');
    }
    
}
