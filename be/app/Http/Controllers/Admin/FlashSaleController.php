<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FlashSale;
use App\Models\Product;

class FlashSaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $flashSales = FlashSale::with('product')->orderByDesc('id')->get();
        return view('admin.flashsale.index', compact('flashSales'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = \App\Models\Category::all();
        $products = \App\Models\Product::all();
        return view('admin.flashsale.create', compact('categories', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,Product_ID',
            'price_sale' => 'required|numeric|min:0',
            'price_old' => 'nullable|numeric|min:0',
            'discount' => 'nullable|integer|min:0|max:100',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date|after_or_equal:start_time',
            'status' => 'boolean',
        ]);

        FlashSale::create($request->all());

        // Chuyển về trang danh sách flash sale
        return redirect()->route('admin.flash-sales.index')->with('success', 'Thêm Flash Sale thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $flashSale = FlashSale::with('product')->findOrFail($id);
        return view('admin.flashsale.show', compact('flashSale'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $flashSale = FlashSale::findOrFail($id);
        $categories = \App\Models\Category::all();
        $products = \App\Models\Product::all();
        return view('admin.flashsale.edit', compact('flashSale', 'categories', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $flashSale = \App\Models\FlashSale::findOrFail($id);

        $data = $request->all();
        // Đảm bảo luôn cập nhật trường is_show (checkbox hoặc nút)
        if ($request->has('is_show')) {
            $data['is_show'] = $request->input('is_show') ? 1 : 0;
        } else {
            $data['is_show'] = $flashSale->is_show;
        }

        $flashSale->update($data);

        return redirect()->route('admin.flash-sales.index')->with('success', 'Cập nhật Flash Sale thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $flashSale = FlashSale::findOrFail($id);
        $flashSale->delete();

        // Chuyển về trang danh sách flash sale
        return redirect()->route('admin.flash-sales.index')->with('success', 'Xóa Flash Sale thành công!');
    }
}
