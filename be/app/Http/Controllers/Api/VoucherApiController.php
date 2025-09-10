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
            'max_uses' => 'nullable|integer',
            'expires' => 'nullable|date',
            'applies_to' => 'nullable|string',
            'paid_at' => 'nullable|date',
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
            'max_uses' => 'nullable|integer',
            'expires' => 'nullable|date',
            'applies_to' => 'nullable|string',
            'paid_at' => 'nullable|date',
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
        $isBooking = $request->input('is_booking', false); // true nếu là đặt sân
        $cartCategories = $request->input('cart_categories', []); // mảng tên danh mục sản phẩm

        $voucher = Voucher::where('code', $code)
            ->where(function($q) {
                $q->whereNull('expires')->orWhere('expires', '>=', now());
            })
            ->first();

        if (!$voucher) {
            return response()->json(['valid' => false, 'message' => 'Mã không hợp lệ hoặc đã hết hạn']);
        }

        // --- Bắt đầu kiểm tra applies_to ---
        $appliesTo = trim(mb_strtolower($voucher->applies_to));

        $canApply = false;
        if ($appliesTo === 'all') {
            $canApply = true;
        } elseif ($isBooking && $appliesTo === 'booking') {
            $canApply = true;
        } elseif (!$isBooking && $appliesTo !== 'booking' && $appliesTo !== 'all') {
            $allowed = array_map('mb_strtolower', array_map('trim', explode(',', $voucher->applies_to)));
            foreach ($cartCategories as $cat) {
                if (in_array(mb_strtolower(trim($cat)), $allowed)) {
                    $canApply = true;
                    break;
                }
            }
        }

        if (!$canApply) {
            return response()->json(['valid' => false, 'message' => 'Voucher không áp dụng cho đơn hàng này']);
        }
        // --- Kết thúc kiểm tra applies_to ---

        return response()->json(['valid' => true, 'voucher' => $voucher]);
    }
}
