<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductValue;
use App\Models\ProductAttribute;
use Illuminate\Http\Request;

class ProductValueController extends Controller
{
    public function index()
    {
        $values = ProductValue::with('attribute')->paginate(10);
        return view('admin.product_values.index', compact('values'));
    }

    public function create()
    {
        $attributes = ProductAttribute::all();
        return view('admin.product_values.create', compact('attributes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'Attributes_ID' => 'required|exists:product_attributes,Attributes_ID',
            'Value' => 'required|string|max:255',
        ]);

        $validated['Create_at'] = now();

        ProductValue::create($validated);

        return redirect()->route('admin.product_values.index')
            ->with('success', 'Thêm giá trị thành công!');
    }

    public function edit($id)
    {
        $value = ProductValue::findOrFail($id);
        $attributes = ProductAttribute::all();
        return view('admin.product_values.edit', compact('value', 'attributes'));
    }

    public function update(Request $request, $id)
    {
        $value = ProductValue::findOrFail($id);

        $validated = $request->validate([
            'Attributes_ID' => 'required|exists:product_attributes,Attributes_ID',
            'Value' => 'required|string|max:255',
        ]);

        $validated['Update_at'] = now();

        $value->update($validated);

        return redirect()->route('admin.product_values.index')
            ->with('success', 'Cập nhật giá trị thành công!');
    }

    public function destroy($id)
    {
        $value = ProductValue::findOrFail($id);
        $value->delete();

        return redirect()->route('admin.product_values.index')
            ->with('success', 'Xóa giá trị thành công!');
    }
    public function attribute(): BelongsTo
{
    return $this->belongsTo(ProductAttribute::class, 'Attributes_ID', 'Attributes_ID');
}

}
