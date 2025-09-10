<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderDetail;
use Illuminate\Http\RedirectResponse;

class OrderDetailController extends Controller
{
    public function destroy($id): RedirectResponse
    {
        $detail = OrderDetail::findOrFail($id);
        $orderId = $detail->order_id;

        $detail->delete();

        return redirect()->route('admin.orders.show', $orderId)
            ->with('success', 'Xoá sản phẩm khỏi đơn hàng thành công!');
    }
}
