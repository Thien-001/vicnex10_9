<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;

class OrderDetailApi extends Controller
{
    public function index()
    {
        return response()->json(OrderDetail::all(), 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id', // Sửa lại nếu bảng orders dùng id
            'Product_ID' => 'required|exists:products,Product_ID',
            'product_name' => 'required|string|max:255',
            'SKU' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            // Không cần validate 'total' từ FE
        ]);

        // Tính thành tiền ở backend
        $validated['total'] = $validated['price'] * $validated['quantity'];

        $detail = OrderDetail::create($validated);
        return response()->json($detail, 201);
    }

    public function show($id)
    {
        $detail = OrderDetail::find($id);
        if (!$detail) {
            return response()->json(['message' => 'Không tìm thấy chi tiết đơn hàng'], 404);
        }

        return response()->json($detail, 200);
    }

    public function update(Request $request, $id)
    {
        $detail = OrderDetail::find($id);
        if (!$detail) {
            return response()->json(['message' => 'Không tìm thấy chi tiết đơn hàng'], 404);
        }

        $validated = $request->validate([
            'product_name' => 'sometimes|required|string|max:255',
            'SKU' => 'sometimes|required|string|max:100',
            'price' => 'sometimes|required|numeric|min:0',
            'quantity' => 'sometimes|required|integer|min:1',
            'total' => 'sometimes|required|numeric|min:0',
        ]);

        if (isset($validated['price']) || isset($validated['quantity'])) {
            $price = $validated['price'] ?? $detail->price;
            $quantity = $validated['quantity'] ?? $detail->quantity;
            $validated['total'] = $price * $quantity;
        }

        $detail->update($validated);
        return response()->json($detail, 200);
    }

    public function destroy($id)
    {
        $detail = OrderDetail::find($id);
        if (!$detail) {
            return response()->json(['message' => 'Không tìm thấy chi tiết đơn hàng'], 404);
        }

        $detail->delete();
        return response()->json(['message' => 'Xóa chi tiết đơn hàng thành công'], 200);
    }

    public function createOrderDetail($order, $item, $price, $quantity)
    {
        // Tạo đơn hàng trước
        $order = Order::create([
            // ...các trường đơn hàng...
        ]);
        // Sau đó mới tạo chi tiết đơn hàng
        OrderDetail::create([
            'order_id'     => $order->id,
            'Product_ID'   => $item['Product_ID'],
            'product_name' => $item['Name'],
            'SKU'          => $item['SKU'] ?? null,
            'price'        => $price,
            'quantity'     => $quantity,
            'total'        => $price * $quantity,
        ]);
    }
}
