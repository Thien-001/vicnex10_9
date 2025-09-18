<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use App\Mail\OrderSuccessMail;
use Illuminate\Support\Facades\Mail;
use App\Models\Notification;

class OrderApi extends Controller
{
    // Lấy tất cả đơn hàng (admin)
    public function index()
    {
        return response()->json(Order::with(['user', 'orderDetails.product'])->get(), 200);
    }

    // Tạo đơn hàng mới
    public function store(Request $request)
    {
        // Debug: Kiểm tra dữ liệu nhận được từ frontend
        \Log::info('Dữ liệu đặt hàng:', $request->all());

        // Validate dữ liệu đầu vào
        $request->validate([
            'full_name'      => 'required|string',
            'phone'          => 'required|string',
            'email'          => 'required|email',
            'province_code'  => 'required',
            'district_code'  => 'required',
            'ward_code'      => 'required',
            'address'        => 'required|string',
            'note'           => 'nullable|string',
            'status'         => 'required|string',
            'payment_method' => 'required|string',
            'total_price'    => 'required|numeric|min:1',
            'shipping_fee'   => 'required|numeric|min:0',
            'voucher_id'     => 'nullable',
            'order_details'  => 'required|array|min:1',
            'order_details.*.Product_ID' => 'required|integer',
            'order_details.*.quantity' => 'required|integer|min:1',
            'order_details.*.price' => 'required|numeric|min:0',
            'order_details.*.total_price' => 'required|numeric|min:0',
            'order_details.*.variant_id' => 'nullable|integer', // THÊM DÒNG NÀY
        ]);

        // Lấy thông tin từ request
        $area = $request->input('area'); // 'noithanh', 'ngoaithanh', 'tinhxa'
        $distanceKm = $request->input('distance_km'); // số km
        $total = $request->input('total_price');

        // Tính phí ship
        $shippingFee = $this->calculateShippingFee($total, $area, $distanceKm);

        // Lấy user_id từ request hoặc từ user đang đăng nhập
        $userId = $request->input('user_id') ?? (auth()->check() ? auth()->id() : null);

        // Tạo đơn hàng
        $order = Order::create([
            'full_name'      => $request->input('full_name'),
            'phone'          => $request->input('phone'),
            'email'          => $request->input('email'),
            'province_code'  => $request->input('province_code'),
            'district_code'  => $request->input('district_code'),
            'ward_code'      => $request->input('ward_code'),
            'address'        => $request->input('address'),
            'note'           => $request->input('note'),
            'status'         => $request->input('status'),
            'payment_method' => $request->input('payment_method'),
            'total_price'    => $total,
            'shipping_fee'   => $shippingFee,
            'voucher_id'     => $request->input('voucher_id'),
            'user_id'        => $userId,
        ]);

        // Tạo chi tiết đơn hàng
        foreach ($request->input('order_details', []) as $item) {
            // Trừ kho sản phẩm gốc
            if (isset($item['Product_ID'])) {
                $product = Product::where('Product_ID', $item['Product_ID'])->first();
                if ($product) {
                    $product->quantity = max(0, $product->quantity - $item['quantity']);
                    $product->save();
                }
            }

            // Trừ kho biến thể nếu có
            if (isset($item['variant_id'])) {
                \Log::info('Xử lý biến thể:', $item); // Log dữ liệu biến thể nhận được
                $variant = ProductVariant::find($item['variant_id']);
                if ($variant) {
                    \Log::info('Trước khi trừ kho biến thể:', [
                        'variant_id' => $item['variant_id'],
                        'quantity_trong_kho' => $variant->Quantity,
                        'quantity_dat_hang' => $item['quantity']
                    ]);
                    if ($variant->Quantity < $item['quantity']) {
                        \Log::error('Không đủ kho biến thể:', [
                            'variant_id' => $item['variant_id'],
                            'quantity_trong_kho' => $variant->Quantity,
                            'quantity_dat_hang' => $item['quantity']
                        ]);
                        return response()->json(['error' => 'Sản phẩm biến thể đã hết hàng!'], 400);
                    }
                    // TRỪ KHO BIẾN THỂ
                    \Log::info('Trước khi trừ kho:', ['variant_id' => $item['variant_id'], 'quantity' => $variant->Quantity]);
                    $variant->Quantity = max(0, $variant->Quantity - $item['quantity']);
                    $variant->save();
                    \Log::info('Sau khi trừ kho:', ['variant_id' => $item['variant_id'], 'quantity' => $variant->Quantity]);
                } else {
                    \Log::error('Không tìm thấy biến thể:', ['variant_id' => $item['variant_id']]);
                }
            }

            // Tạo chi tiết đơn hàng
            OrderDetail::create([
                'order_id'      => $order->id,
                'Product_ID'    => $item['Product_ID'],
                'quantity'      => $item['quantity'],
                'price'         => $item['price'],
                'discount_price'=> $item['discount_price'] ?? 0,
                'total_price'   => $item['total_price'],
                'product_name'  => $item['product_name'] ?? '',
                'variant_id'    => $item['variant_id'] ?? null,
            ]);
        }

        // Lấy lại đơn hàng đầy đủ thông tin
        $order = Order::with(['orderDetails.product', 'user'])->find($order->id);

        // Gửi email xác nhận cho khách hàng (chỉ gửi nếu có email hợp lệ)
        if (!empty($order->email)) {
            try {
                Mail::to($order->email)->send(new OrderSuccessMail($order));
            } catch (\Exception $e) {
                \Log::error('Lỗi gửi email xác nhận đơn hàng: ' . $e->getMessage());
                // Không throw lỗi để tránh làm hỏng quá trình đặt hàng
            }
        }

        // Gửi thông báo cho user
        if ($userId) {
            Notification::create([
                'User_ID' => $userId,
                'Title' => 'Đặt hàng thành công',
                'Message' => 'Cảm ơn bạn đã đặt hàng tại Vicnex!',
                'Type' => 'order',
            ]);
        }

        return response()->json($order, 201);
    }

    // Lấy đơn hàng theo id
    public function show($id)
    {
        $order = Order::with(['orderDetails.product', 'user'])->find($id);
        if (!$order) {
            return response()->json(['message' => 'Không tìm thấy đơn hàng'], 404);
        }
        return response()->json($order, 200);
    }

    // Cập nhật đơn hàng
    public function update(Request $request, $id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['message' => 'Không tìm thấy đơn hàng'], 404);
        }

        $validated = $request->validate([
            'shipping_address' => 'sometimes|required|string',
            'note_user' => 'nullable|string',
            'payment_method' => 'sometimes|required|string',
            'shipping_fee' => 'nullable|numeric',
            'total_price' => 'sometimes|required|numeric',
            'status' => 'sometimes|required|string',
            'status_method' => 'nullable|string',
            'voucher_id' => 'nullable|exists:vouchers,id',
        ]);

        $order->update($validated);
        return response()->json(Order::with(['orderDetails.product', 'user'])->find($id), 200);
    }

    // Xóa đơn hàng
    public function destroy($id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['message' => 'Không tìm thấy đơn hàng'], 404);
        }

        $order->delete();
        return response()->json(['message' => 'Xóa đơn hàng thành công'], 200);
    }

    // Lấy lịch sử đơn hàng của user
    public function getOrdersByUser($id)
    {
        $orders = Order::where('user_id', $id)
            ->with(['orderDetails.product', 'voucher'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Biến đổi dữ liệu để thêm voucher_code
        $orders->getCollection()->transform(function ($order) {
            return [
                'id' => $order->id,
                'created_at' => $order->created_at,
                'status' => $order->status,
                'full_name' => $order->full_name,
                'phone' => $order->phone,
                'address' => $order->address,
                'payment_method' => $order->payment_method,
                'total_price' => $order->total_price,
                'shipping_fee' => $order->shipping_fee,
                'order_details' => $order->orderDetails,
                'voucher_code' => $order->voucher ? $order->voucher->code : null,
                // ...bạn có thể thêm các trường khác nếu cần...
            ];
        });

        return response()->json($orders, 200);
    }

    // Lấy sản phẩm theo slug
    public function getProductBySlug($slug)
    {
        $product = Product::where('slug', $slug)->first();
        if (!$product) {
            abort(404);
        }
        return response()->json($product);
    }

    // Hủy đơn hàng
    public function cancelOrder($id)
    {
        $order = Order::findOrFail($id);
        if ($order->status !== 'pending') {
            return response()->json(['message' => 'Không thể hủy đơn đã xác nhận hoặc đã xử lý!'], 400);
        }
        $order->status = 'cancelled';
        $order->save();
        return response()->json(['message' => 'Đã hủy đơn hàng!']);
    }

    // Kiểm tra xem người dùng đã mua sản phẩm này chưa
    public function checkPurchased(Request $request)
    {
        $user_id = $request->query('user_id');
        $product_id = $request->query('product_id');

        $order = \App\Models\Order::where('user_id', $user_id)
            ->where('status', 'completed')
            ->whereHas('orderDetails', function($q) use ($product_id) {
                $q->where('Product_ID', $product_id);
            })
            ->first();

        return response()->json(['purchased' => !!$order]);
    }

    private function calculateShippingFee($total, $area, $distanceKm)
    {
        if ($total >= 500000) {
            return 0;
        }
        // Theo khu vực
        $areaFee = 0;
        switch ($area) {
            case 'noithanh':
                $areaFee = 20000;
                break;
            case 'ngoaithanh':
                $areaFee = 40000;
                break;
            case 'tinhxa':
                $areaFee = 60000;
                break;
            default:
                $areaFee = 40000;
        }
        // Theo khoảng cách
        if ($distanceKm <= 5) {
            $distanceFee = 15000;
        } elseif ($distanceKm <= 10) {
            $distanceFee = 25000;
        } else {
            $distanceFee = 40000;
        }
        return max($areaFee, $distanceFee);
    }
}
