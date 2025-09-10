<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\ProductAttribute;
use App\Models\CategoryAttribute;

class CategoryAttributeController extends Controller
{
    public function create()
    {
        $categories = Category::all();
        $attributes = ProductAttribute::all();

        return view('admin.category_attribute.create', compact('categories', 'attributes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,Categories_ID',
            'attribute_ids' => 'required|array',
        ]);

        foreach ($request->attribute_ids as $attrId) {
            CategoryAttribute::create([
                'category_id' => $request->category_id,
                'attribute_id' => $attrId,
            ]);
        }

       return redirect()->route('admin.category-attribute.create')->with('success', '...');

    }
}
