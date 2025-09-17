<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Voucher;

class VoucherApiController extends Controller
{
    // Lấy danh sách tất cả voucher
    public function index()
    {
        return response()->json(Voucher::all(), 200);
    }

    // Tạo mới voucher
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|unique:vouchers,code',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric',
            'min_order_value' => 'nullable|numeric',
            'max_discount' => 'nullable|numeric',
            'max_uses' => 'nullable|integer',
            'expires' => 'nullable|date',
            'applies_to' => 'nullable|string',
            'paid_at' => 'nullable|date',
            'description' => 'nullable|string',
        ]);

        $voucher = Voucher::create($validated);
        return response()->json($voucher, 201);
    }

    // Lấy thông tin 1 voucher
    public function show($id)
    {
        $voucher = Voucher::find($id);
        if (!$voucher) {
            return response()->json(['message' => 'Not found'], 404);
        }
        return response()->json($voucher, 200);
    }

    // Cập nhật voucher
    public function update(Request $request, $id)
    {
        $voucher = Voucher::find($id);
        if (!$voucher) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $validated = $request->validate([
            'code' => 'sometimes|required|unique:vouchers,code,' . $id . ',id',
            'discount_type' => 'sometimes|required|in:percentage,fixed',
            'discount_value' => 'sometimes|required|numeric',
            'min_order_value' => 'nullable|numeric',
            'max_discount' => 'nullable|numeric',
            'max_uses' => 'nullable|integer',
            'expires' => 'nullable|date',
            'applies_to' => 'nullable|string',
            'paid_at' => 'nullable|date',
            'description' => 'nullable|string',
        ]);

        $voucher->update($validated);
        return response()->json($voucher, 200);
    }

    // Xóa voucher
    public function destroy($id)
    {
        $voucher = Voucher::find($id);
        if (!$voucher) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $voucher->delete();
        return response()->json(['message' => 'Deleted'], 200);
    }

    // Kiểm tra mã voucher hợp lệ cho frontend
    public function check(Request $request)
    {
        $code = $request->input('code');
        $isBooking = $request->input('is_booking', false);
        $cartCategoryIds = $request->input('cart_category_ids', []);

        $voucher = Voucher::where('code', $code)
            ->where(function($q) {
                $q->whereNull('expires')->orWhere('expires', '>=', now());
            })
            ->first();

        if (!$voucher) {
            return response()->json(['valid' => false, 'message' => 'Mã không hợp lệ hoặc đã hết hạn']);
        }

        // --- Chuẩn hóa danh mục ---
        $appliesTo = $voucher->applies_to; // 'all', 'booking', hoặc chuỗi ID: "1,2,3"
        $canApply = false;
        $allowedIds = [];

        if ($appliesTo === 'all') {
            $canApply = true;
        } elseif ($isBooking && $appliesTo === 'booking') {
            $canApply = true;
        } elseif (!$isBooking && $appliesTo !== 'booking' && $appliesTo !== 'all') {
            $allowedIds = array_map('intval', explode(',', $appliesTo));
            foreach ($cartCategoryIds as $catId) {
                if (in_array((int)$catId, $allowedIds)) {
                    $canApply = true;
                    break;
                }
            }
        }

        if (!$canApply) {
            return response()->json(['valid' => false, 'message' => 'Voucher không áp dụng cho đơn hàng này']);
        }

        // Kiểm tra giá trị đơn hàng tối thiểu nếu có
        $minOrderValue = $voucher->min_order_value ?? 0;
        $eligibleSubtotal = 0;
        if ($appliesTo === 'all') {
            // Tính tổng tất cả sản phẩm
            $eligibleSubtotal = $request->input('cart_subtotal', 0);
        } else {
            // Tính tổng sản phẩm thuộc danh mục hợp lệ
            $eligibleItems = $request->input('eligible_items', []);
            foreach ($eligibleItems as $item) {
                $eligibleSubtotal += $item['price'] * $item['qty'];
            }
        }
        if ($minOrderValue > 0 && $eligibleSubtotal < $minOrderValue) {
            return response()->json(['valid' => false, 'message' => 'Đơn hàng chưa đạt giá trị tối thiểu để áp dụng voucher']);
        }

        return response()->json([
            'valid' => true,
            'voucher' => $voucher,
            'category_id' => $appliesTo === 'all' ? null : $allowedIds[0] ?? null,
            'category_ids' => $appliesTo === 'all' ? [] : $allowedIds
        ]);
    }
}
