<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductAttribute;

class ProductAttributeApiController extends Controller
{
    public function index()
    {
        $attributes = ProductAttribute::all();
        return response()->json($attributes);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'Name' => 'required|string|max:255',
            'Description' => 'nullable|string|max:1000',
        ]);

        $validated['Create_at'] = now();

        $attribute = ProductAttribute::create($validated);
        return response()->json($attribute, 201);
    }

    public function show($id)
    {
        $attribute = ProductAttribute::findOrFail($id);
        return response()->json($attribute);
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
        return response()->json($attribute);
    }

    public function destroy($id)
    {
        $attribute = ProductAttribute::findOrFail($id);
        $attribute->delete();

        return response()->json(['message' => 'Xóa thành công']);
    }
}
