<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductValue;
use Illuminate\Http\Request;

class ProductValueApiController extends Controller
{
    public function index()
    {
        $values = ProductValue::with('attribute')->get(); // Lấy kèm thuộc tính
        return response()->json($values);
    }

    public function show($id)
    {
        $value = ProductValue::with('attribute')->findOrFail($id);
        return response()->json($value);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'Attributes_ID' => 'required|exists:product_attributes,Attributes_ID',
            'Value' => 'required|string|max:255',
        ]);

        $validated['Create_at'] = now();

        $value = ProductValue::create($validated);

        return response()->json($value, 201);
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

        return response()->json($value);
    }

    public function destroy($id)
    {
        $value = ProductValue::findOrFail($id);
        $value->delete();

        return response()->json(['message' => 'Đã xóa giá trị']);
    }
}
