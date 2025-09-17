<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\Voucher;
use App\Models\Category; // Thêm dòng này
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VoucherController extends Controller
{
    public function index()
    {
        $vouchers = Voucher::paginate(10);
        $categories = \App\Models\Category::all();
        return view('admin.vouchers.index', compact('vouchers', 'categories'));
    }

    public function create()
    {
        $categories = \App\Models\Category::all(); // Lấy tất cả danh mục
        return view('admin.vouchers.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|unique:vouchers,code',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric',
            'max_uses' => 'nullable|integer',
            'expires' => 'nullable|date',
            'applies_to' => 'nullable|string',
        ]);

        if ($request->applies_to == 'all' || $request->applies_to == 'booking') {
            $applies_to = $request->applies_to;
        } else {
            $applies_to = $request->has('applies_to_categories')
                ? implode(',', $request->applies_to_categories) // Lưu ID, không phải tên
                : null;
        }

        Voucher::create([
            'code' => $validated['code'],
            'discount_type' => $validated['discount_type'],
            'discount_value' => $validated['discount_value'],
            'max_uses' => $validated['max_uses'] ?? null,
            'expires' => $validated['expires'] ?? null,
            'applies_to' => $applies_to,
        ]);

        return redirect()->route('admin.vouchers.index')->with('success', 'Tạo voucher thành công.');
    }

    public function edit($id)
    {
        $voucher = Voucher::findOrFail($id);
        $categories = \App\Models\Category::all();
        return view('admin.vouchers.edit', compact('voucher', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:255|unique:vouchers,code,' . $id . ',id',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'max_uses' => 'nullable|integer|min:0',
            'expires' => 'nullable|date',
            'applies_to' => 'nullable|string|max:255',
        ]);

        $voucher = Voucher::findOrFail($id);

        if ($request->applies_to == 'all' || $request->applies_to == 'booking') {
            $applies_to = $request->applies_to;
        } else {
            $applies_to = $request->has('applies_to_categories')
                ? implode(',', $request->applies_to_categories) // Lưu ID, không phải tên
                : null;
        }

        $voucher->update([
            'code' => $validated['code'],
            'discount_type' => $validated['discount_type'],
            'discount_value' => $validated['discount_value'],
            'max_uses' => $validated['max_uses'] ?? null,
            'expires' => $validated['expires'] ?? null,
            'applies_to' => $applies_to,
        ]);

        return redirect()->route('admin.vouchers.index')->with('success', 'Cập nhật thành công');
    }

    public function destroy($id)
    {
        $voucher = Voucher::findOrFail($id);
        $voucher->delete();
        return redirect()->route('admin.vouchers.index')->with('success', 'Xoá thành công.');
    }
}
