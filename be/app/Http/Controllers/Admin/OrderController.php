<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Province;
use App\Models\District;
use App\Models\Ward;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // Hiển thị danh sách đơn hàng
    public function index(Request $request)
    {
        $query = Order::with('user');

        if ($request->filled('order_code')) {
            $query->where('order_code', 'like', '%' . $request->order_code . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->orderByDesc('created_at')->paginate(10)->appends($request->query());

        return view('admin.orders.index', compact('orders'));
    }

    // Hiển thị form tạo đơn hàng mới
    public function create()
    {
        $users = User::all();
        return view('admin.orders.create', compact('users'));
    }

    // Lưu đơn hàng mới
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name'      => 'required|string|max:255',
            'phone'          => 'required|string|max:20',
            'email'          => 'nullable|email',
            'province_code'  => 'required|string',
            'district_code'  => 'required|string',
            'ward_code'      => 'required|string',
            'address'        => 'required|string|max:255',
            'note'           => 'nullable|string',
            'status'         => 'required|string',
            'payment_method' => 'required|string',
            'total_price'    => 'required|numeric|min:0',
            'shipping_fee'   => 'nullable|numeric|min:0',
            'voucher_id'     => 'nullable|exists:vouchers,id',
            'user_id'        => 'nullable|exists:users,id',
        ]);

        // Đảm bảo lưu voucher_id vào đơn hàng
        $order = Order::create($validated);

        return redirect()->route('admin.orders.show', $order->id)->with('success', 'Tạo đơn hàng thành công!');
    }

    // Xem chi tiết đơn hàng
    public function show($id)
    {
        // Lấy cả thông tin voucher
        $order = Order::with(['user', 'details.product', 'voucher'])->findOrFail($id);

        $order->province_name = Province::where('code', $order->province_code)->value('name');
        $order->district_name = District::where('code', $order->district_code)->value('name');
        $order->ward_name     = Ward::where('code', $order->ward_code)->value('name');

        // Trả về mã voucher nếu có
        $order->voucher_code = $order->voucher ? $order->voucher->code : null;

        return view('admin.orders.show', compact('order'));
    }

    // Hiển thị form sửa đơn hàng
    public function edit($id)
    {
        $order = Order::with(['details.product'])->findOrFail($id);
        $users = User::all();
        return view('admin.orders.edit', compact('order', 'users'));
    }

    // Cập nhật trạng thái và user_id đơn hàng
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'shipping_address' => 'required|string|max:255',
            'note_user'        => 'nullable|string',
            'payment_method'   => 'required|string',
            'shiping_fee'      => 'nullable|numeric|min:0',
            'status'           => 'required|string',
            'status_method'    => 'nullable|string',
            'user_id'          => 'nullable|exists:users,id', // Thêm user_id
        ]);

        $order = Order::findOrFail($id);

        if ($order->status === 'cancelled') {
            return redirect()->back()->with('error', 'Đơn hàng đã hủy và không thể cập nhật trạng thái.');
        }

        $order->shipping_address = $validated['shipping_address'];
        $order->note_user        = $validated['note_user'];
        $order->payment_method   = $validated['payment_method'];
        $order->shiping_fee      = $validated['shiping_fee'] ?? 0;
        $order->status           = $validated['status'];
        $order->user_id          = $validated['user_id'] ?? $order->user_id; // Cập nhật user_id nếu có
        $order->updated_at       = now();

        $order->save();

        return redirect()->route('admin.orders.index')->with('success', 'Cập nhật đơn hàng thành công!');
    }

    // Thống kê đơn hàng
    public function statistics()
    {
        $totalOrders = Order::count();
        $totalRevenue = Order::sum('total_amount');
        $todayOrders = Order::whereDate('created_at', now()->toDateString())->count();
        $completedOrders = Order::where('status', 'completed')->count();
        $cancelledOrders = Order::where('status', 'cancelled')->count();

        $orders = Order::selectRaw('DATE(created_at) as date, SUM(total_amount) as revenue')
            ->where('created_at', '>=', now()->subDays(6))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $chartLabels = $orders->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d/m'));
        $chartData = $orders->pluck('revenue');

        return view('admin.orders.statistics', compact(
            'totalOrders',
            'totalRevenue',
            'todayOrders',
            'completedOrders',
            'cancelledOrders',
            'chartLabels',
            'chartData'
        ));
    }

    // Xóa đơn hàng
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return redirect()->route('admin.orders.index')->with('success', 'Đã xóa đơn hàng thành công.');
    }

    // Thêm chi tiết đơn hàng
    public function addOrderDetails(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        foreach ($request->cart as $item) {
            $price = isset($item['Discount_price']) && $item['Discount_price'] > 0
                ? $item['Discount_price']
                : $item['Price'];
            $quantity = $item['quantity'] ?? 1;

            // Lấy tên sản phẩm từ bảng products nếu chưa có
            $productName = $item['Name'] ?? $item['product_name'] ?? '';
            if (!$productName && isset($item['Product_ID'])) {
                $product = \App\Models\Product::where('Product_ID', $item['Product_ID'])->first();
                $productName = $product ? $product->Product_Name : '';
            }

            OrderDetail::create([
                'order_id'     => $order->id,
                'Product_ID'   => $item['Product_ID'],
                'product_name' => $productName,
                'SKU'          => $item['SKU'] ?? $item['sku'] ?? '',
                'price'        => $price,
                'quantity'     => $quantity,
                'total'        => $price * $quantity,
            ]);
        }

        return redirect()->route('admin.orders.show', $order->id)->with('success', 'Thêm chi tiết đơn hàng thành công!');
    }

    // Xác nhận đơn hàng
    public function confirm($id)
    {
        $order = Order::findOrFail($id);
        if ($order->status === 'pending') {
            $order->status = 'confirmed';
            $order->save();
            return redirect()->back()->with('success', 'Đã xác nhận đơn hàng!');
        }
        return redirect()->back()->with('error', 'Chỉ xác nhận được đơn hàng đang chờ xử lý!');
    }

    // Hủy đơn hàng
    public function cancel($id)
    {
        $order = Order::findOrFail($id);
        if ($order->status === 'pending') {
            $order->status = 'cancelled';
            $order->save();
            return redirect()->back()->with('success', 'Đã hủy đơn hàng!');
        }
        return redirect()->back()->with('error', 'Không thể hủy đơn hàng đã xác nhận hoặc đã hủy!');
    }

    // Xác nhận đã giao hàng
    public function ship($id)
    {
        $order = Order::findOrFail($id);
        if ($order->status === 'shipping' || $order->status === 'confirmed') {
            $order->status = 'completed'; // hoặc 'shipped' nếu bạn muốn trạng thái riêng
            $order->save();
            return redirect()->back()->with('success', 'Đã xác nhận giao hàng thành công!');
        }
        return redirect()->back()->with('error', 'Chỉ xác nhận giao hàng cho đơn đang giao hoặc đã xác nhận!');
    }

    // Chuyển trạng thái đơn hàng sang "Đang giao"
    public function shipping($id)
    {
        $order = Order::findOrFail($id);
        if ($order->status === 'confirmed') {
            $order->status = 'shipping';
            $order->save();
            return redirect()->back()->with('success', 'Đơn hàng đã chuyển sang trạng thái Đang giao!');
        }
        return redirect()->back()->with('error', 'Chỉ chuyển sang Đang giao với đơn đã xác nhận!');
    }
}
