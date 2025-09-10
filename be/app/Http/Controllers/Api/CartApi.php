<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;

class CartApi extends Controller
{
    // GET /api/carts
    public function index()
    {
        return response()->json(
            Cart::with(['user', 'product'])->get(),
            200
        );
    }

    // POST /api/carts
    public function store(Request $request)
    {
        $data = $request->validate([
            'User_ID'    => 'required|exists:user,ID',
            'Product_ID' => 'required|exists:products,Product_ID',
            'SKU'        => 'nullable|string|max:100', // Thêm validate SKU biến thể
            'Quantity'   => 'required|integer|min:1',
            'Price'      => 'required|numeric|min:0',
        ]);

        // Nếu đã có sản phẩm này (cùng Product_ID và SKU) trong giỏ, thì cộng dồn số lượng
        $cart = Cart::where('User_ID', $data['User_ID'])
            ->where('Product_ID', $data['Product_ID'])
            ->where('SKU', $data['SKU'] ?? null)
            ->first();

        if ($cart) {
            $cart->Quantity += $data['Quantity'];
            $cart->Price = $data['Price']; // cập nhật giá mới nhất
            $cart->save();
        } else {
            $cart = Cart::create($data);
        }

        $cart->load(['user', 'product']);

        return response()->json([
            'message' => 'Thêm sản phẩm vào giỏ hàng thành công',
            'data'    => $cart
        ], 201);
    }

    // GET /api/carts/{id}
    public function show($id)
    {
        $cart = Cart::with(['user', 'product'])->find($id);

        if (!$cart) {
            return response()->json(['message' => 'Không tìm thấy giỏ hàng'], 404);
        }

        return response()->json($cart);
    }

    // PUT /api/carts/{id}
    public function update(Request $request, $id)
    {
        $cart = Cart::find($id);

        if (!$cart) {
            return response()->json(['message' => 'Không tìm thấy giỏ hàng'], 404);
        }

        $data = $request->validate([
            'Quantity' => 'sometimes|required|integer|min:1',
            'Price'    => 'sometimes|required|numeric|min:0',
        ]);

        $cart->update($data);
        $cart->load(['user', 'product']);

        return response()->json([
            'message' => 'Cập nhật giỏ hàng thành công',
            'data'    => $cart
        ]);
    }

    // DELETE /api/carts/{id}
    public function destroy($id)
    {
        $cart = Cart::find($id);

        if (!$cart) {
            return response()->json(['message' => 'Không tìm thấy giỏ hàng'], 404);
        }

        $cart->delete();

        return response()->json(['message' => 'Xóa sản phẩm khỏi giỏ hàng thành công']);
    }
}

