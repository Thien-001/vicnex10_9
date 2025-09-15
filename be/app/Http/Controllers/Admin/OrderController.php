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

        // Tìm kiếm theo mã đơn hàng (tìm theo ID hoặc mã hệ thống)
        if ($request->filled('order_code')) {
            $searchTerm = $request->order_code;

            $query->where(function($q) use ($searchTerm) {
                // Tìm theo ID
                $q->where('id', 'like', '%' . $searchTerm . '%')
                  // Tìm theo mã đơn hàng hệ thống (DH + ID)
                  ->orWhereRaw("CONCAT('DH', LPAD(id, 6, '0')) LIKE ?", ['%' . $searchTerm . '%']);
            });
        }

        // Tìm kiếm theo tên khách hàng
        if ($request->filled('customer_name')) {
            $query->where(function($q) use ($request) {
                $q->where('full_name', 'like', '%' . $request->customer_name . '%')
                  ->orWhereHas('user', function($userQuery) use ($request) {
                      $userQuery->where('Name', 'like', '%' . $request->customer_name . '%');
                  });
            });
        }

        // Tìm kiếm theo số điện thoại
        if ($request->filled('phone')) {
            $query->where(function($q) use ($request) {
                $q->where('phone', 'like', '%' . $request->phone . '%')
                  ->orWhereHas('user', function($userQuery) use ($request) {
                      $userQuery->where('Phone', 'like', '%' . $request->phone . '%');
                  });
            });
        }

        // Lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Lọc theo phương thức thanh toán
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Lọc theo khoảng giá
        if ($request->filled('min_amount')) {
            $query->where('total_price', '>=', $request->min_amount);
        }

        if ($request->filled('max_amount')) {
            $query->where('total_price', '<=', $request->max_amount);
        }

        // Lọc theo ngày
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Kiểm tra nếu có yêu cầu xuất Excel
        if ($request->has('export') && $request->export === 'excel') {
            return $this->exportToExcel($query);
        }

        // Sắp xếp và phân trang
        $orders = $query->orderByDesc('created_at')->paginate(15)->appends($request->query());

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

            // Trừ số lượng kho
            if (!empty($item['SKU'])) {
                // Nếu có SKU, trừ biến thể
                $variant = \App\Models\Variant::where('SKU', $item['SKU'])->first();
                if ($variant) {
                    $variant->Quantity = max(0, $variant->Quantity - ($item['quantity'] ?? 1));
                    $variant->save();
                }
            } else {
                // Nếu không có SKU, trừ sản phẩm gốc
                $product = \App\Models\Product::where('Product_ID', $item['Product_ID'])->first();
                if ($product) {
                    $product->Quantity = max(0, $product->Quantity - ($item['quantity'] ?? 1));
                    $product->save();
                }
            }
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

    // Xuất Excel danh sách đơn hàng
    private function exportToExcel($query)
    {
        $orders = $query->with(['user', 'orderDetails.product'])->get();

        $filename = 'danh_sach_don_hang_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');

            // Thêm BOM để hiển thị tiếng Việt đúng trong Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header
            fputcsv($file, [
                'STT',
                'Mã đơn hàng',
                'Tên khách hàng',
                'Email',
                'Số điện thoại',
                'Địa chỉ',
                'Tổng tiền (VNĐ)',
                'Phí ship (VNĐ)',
                'Trạng thái',
                'Phương thức thanh toán',
                'Ngày tạo',
                'Ngày hoàn thành',
                'Ghi chú'
            ]);

            // Data
            foreach ($orders as $index => $order) {
                fputcsv($file, [
                    $index + 1,
                    'DH' . str_pad($order->id, 6, '0', STR_PAD_LEFT),
                    $order->full_name ?? ($order->user->Name ?? 'N/A'),
                    $order->email ?? ($order->user->Email ?? 'N/A'),
                    $order->phone ?? ($order->user->Phone ?? 'N/A'),
                    $order->address ?? 'N/A',
                    number_format($order->total_price ?? $order->total_amount, 0, ',', '.'),
                    number_format($order->shipping_fee ?? $order->shiping_fee, 0, ',', '.'),
                    $this->getStatusText($order->status),
                    ucfirst($order->payment_method),
                    $order->created_at ? $order->created_at->format('d/m/Y H:i') : 'N/A',
                    $order->updated_at ? $order->updated_at->format('d/m/Y H:i') : '',
                    $order->note ?? 'N/A'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // Chuyển đổi status code thành text
    private function getStatusText($status)
    {
        $statusMap = [
            'pending' => 'Chờ xử lý',
            'confirmed' => 'Đã xác nhận',
            'shipping' => 'Đang giao hàng',
            'completed' => 'Hoàn thành',
            'cancelled' => 'Đã hủy'
        ];

        return $statusMap[$status] ?? $status;
    }
}
