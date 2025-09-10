@extends('layouts.layout')
@section('title', 'Danh sách đơn hàng')

@section('content')
<style>
.admin-form-loc-order {
    background-color: #f9f9f9;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.admin-form-loc-order label {
    display: block;
    margin-bottom: 6px;
    font-weight: 500;
    color: #333;
}

.admin-form-loc-order input[type="text"],
.admin-form-loc-order input[type="date"],
.admin-form-loc-order select {
    width: 180px;
    padding: 8px 10px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 14px;
    background-color: #fff;
    transition: border 0.2s;
}

.admin-form-loc-order input[type="text"]:focus,
.admin-form-loc-order input[type="date"]:focus,
.admin-form-loc-order select:focus {
    border-color: #3b82f6;
    outline: none;
}

.table-orders {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.06);
    overflow: hidden;
}
.table-orders th, .table-orders td {
    padding: 12px 14px;
    text-align: center;
    border-bottom: 1px solid #f1f5f9;
}
.table-orders th {
    background: #f3f6fa;
    font-weight: 600;
    color: #0154b9;
    font-size: 15px;
}
.table-orders tr:hover {
    background: #eaf4ff;
    transition: background 0.2s;
}
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-weight: 500;
    padding: 4px 10px;
    border-radius: 16px;
    font-size: 14px;
}
/* .status-pending { background: #fff7e6; color: #f59e42; }
.status-confirmed { background: #e6f4ff; color: #3b82f6; }
.status-shipping { background: #e0f7fa; color: #009688; }
.status-shipped, .status-completed { background: #e6ffe6; color: #22c55e; }
.status-cancelled { background: #ffe6e6; color: #ef4444; } */


.status-pending { background: #fff7e6; color: #f59e42; }
.status-confirmed { background: #e6f4ff; color: #3b82f6; }
.status-shipping, .status-transported { background: #e0f7fa; color: #009688; }
.status-completed { background: #e6ffe6; color: #22c55e; }
.status-cancelled { background: #ffe6e6; color: #ef4444; }
.action-buttons {
    /* display: flex; */
    gap: 8px;
    justify-content: center;
    flex-wrap: wrap; /* Thêm dòng này để các nút tự động xuống dòng khi thiếu chỗ */
    align-items: center;
    min-width: 120px;
}
.admin-button-table {
    background: #f3f6fa;
    border: none;
    border-radius: 6px;
    padding: 6px 14px;
    cursor: pointer;
    font-weight: 500;
    transition: background 0.2s;
    margin-bottom: 4px; /* Tạo khoảng cách dọc khi xuống dòng */
}
.admin-button-table:hover {
    background: #0154b9;
}
.admin-button-table a {
    color: #fff;
    font-weight: 500;
    text-decoration: none;
}
.admin-button-table:hover a {
    color: #e5e5e5;
}
.table-responsive {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}
.table-orders {
    min-width: 900px; /* hoặc 1000px nếu bạn muốn bảng không bị bóp nhỏ quá */
}
@media (max-width: 900px) {
    .table-orders th, .table-orders td { padding: 8px 6px; font-size: 13px; }
}
</style>
<div class="head-title">
	<div class="left">
        <h1>Đơn hàng</h1>
        <ul class="breadcrumb">
            <li><a href="#">Đơn hàng</a></li>
            <li><i class='bx bx-chevron-right'></i></li>
            <li><a class="active" href="#">Danh sách đơn hàng</a></li>
        </ul>
    </div>
</div>
@if(session('success'))
        <div class="alert alert-success" style="margin: 15px 0;">{{ session('success') }}</div>
    @endif
<div class="body-content">

    <!-- =========================
     Bộ lọc đơn hàng
    ============================ -->
    <form action="{{ route('admin.orders.index') }}" method="GET" style="margin-bottom: 20px;" class="admin-form-loc-order">
        <div style="display: flex; flex-wrap: wrap; gap: 15px; align-items: flex-end;">
            <div>
                <label for="order_code">Mã đơn:</label>
                <input type="text" name="order_code" id="order_code" value="{{ request('order_code') }}">
            </div>

            <div>
                <label for="status">Trạng thái:</label>
                <select name="status" id="status">
                    <option value="">Tất cả</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                    <option value="transported" {{ request('status') == 'transported' ? 'selected' : '' }}>Đang vận chuyển</option>
                    <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Đã giao</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                </select>
            </div>

            <div>
                <label for="date_from">Từ ngày:</label>
                <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}">
            </div>

            <div>
                <label for="date_to">Đến ngày:</label>
                <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}">
            </div>

            <div>
                <button type="submit" class="admin-form-loc">Lọc</button>
                <button type="submit" class="admin-form-loc"><a href="{{ route('admin.products.index') }}">Đặt lại</a></button>
            </div>
        </div>
    </form>

    <div class="table-responsive" style="width:100%;overflow-x:auto;">
        <table class="table-orders">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Mã đơn hàng</th>
                    <th>Khách hàng</th>
                    <th>SĐT</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th>Ngày tạo</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $index => $order)
                <tr>
                    <td>{{ $index + 1 + ($orders->currentPage() - 1) * $orders->perPage() }}</td>
                    <td>
                        <strong style="color:#0051ff;font-size:15px;">{{ $order->order_code }}</strong>
                        <div style="font-size:12px;color:#888;">#{{ $order->id }}</div>
                    </td>
                    <td>
                        <div style="font-weight:600;color:#222;">{{ $order->full_name ?? ($order->user->Name ?? 'Không rõ') }}</div>
                        <div style="font-size:13px;color:#777;">
                            {{ $order->email ?? ($order->user->Email ?? '') }}
                        </div>
                    </td>
                    <td>
                        <div style="font-weight:500;color:#0154b9;">{{ $order->phone ?? ($order->user->Phone ?? '---') }}</div>
                        <div style="font-size:13px;color:#777;">
                            {{ $order->address ?? '' }}
                        </div>
                    </td>
                    <td style="font-weight:600;color:#d32f2f;">
                        {{ number_format($order->total_price, 0, ',', '.') }}₫
                        <div style="font-size:13px;color:#888;">
                            Phí ship: {{ number_format($order->shipping_fee, 0, ',', '.') }}₫<br>
                            <span style="color:#0154b9;">
                                Tổng thanh toán:
                                {{ number_format($order->total_price + $order->shipping_fee, 0, ',', '.') }}₫
                            </span>
                        </div>
                    </td>
                    <td>
                        @switch($order->status)
                            @case('pending')
                                <span class="status-badge status-pending"><i class="fas fa-clock"></i> Chờ xử lý</span>
                                @break
                            @case('confirmed')
                                <span class="status-badge status-confirmed"><i class="fas fa-check-circle"></i> Đã xác nhận</span>
                                @break
                            @case('transported')
                                <span class="status-badge status-transported"><i class="fas fa-truck"></i> Đang vận chuyển</span>
                                @break
                            @case('shipping')
                                <span class="status-badge status-shipping"><i class="fas fa-box"></i> Đang giao</span>
                                @break
                            @case('completed')
                                <span class="status-badge status-completed"><i class="fas fa-star"></i> Hoàn thành</span>
                                @break
                            @case('cancelled')
                                <span class="status-badge status-cancelled"><i class="fas fa-times-circle"></i> Đã hủy</span>
                                @break
                            @default
                                <span class="status-badge">Không rõ</span>
                        @endswitch
                        <div style="font-size:12px;color:#888;">
                            {{ $order->payment_method ? 'Thanh toán: ' . ucfirst($order->payment_method) : '' }}
                        </div>
                    </td>
                    <td>
                        <div>
                            {{ optional($order->created_at)->setTimezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i') }}
                        </div>
                        <div style="font-size:12px;color:#888;">
                            @if($order->updated_at && $order->updated_at != $order->created_at)
                                Cập nhật: {{ optional($order->updated_at)->setTimezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i') }}
                            @endif
                        </div>
                    </td>
                    <td class="action-buttons">
                        <button class="admin-button-table">
                            <a href="{{ route('admin.orders.show', $order->id) }}">Xem</a>
                        </button>
                        @if ($order->status !== 'cancelled' && $order->status !== 'completed')
                            {{-- <button class="admin-button-table">
                                <a href="{{ route('admin.orders.edit', $order->id) }}">Sửa</a>
                            </button> --}}
                            @if ($order->status === 'pending')
                                <form action="{{ route('admin.orders.confirm', $order->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="admin-button-table btn-view" onclick="return confirm('Xác nhận đơn hàng này?')">
                                        Xác nhận
                                    </button>
                                </form>
                                <form action="{{ route('admin.orders.cancel', $order->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="admin-button-table" onclick="return confirm('Bạn chắc chắn muốn hủy đơn hàng này?')">
                                        Hủy
                                    </button>
                                </form>
                            @endif

                            {{-- Nút xác nhận đã giao hàng --}}
                            @if ($order->status === 'shipping' || $order->status === 'confirmed')
                                <form action="{{ route('admin.orders.ship', $order->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="admin-button-table" onclick="return confirm('Xác nhận đã giao hàng cho đơn này?')">
                                        Đã giao hàng
                                    </button>
                                </form>
                            @endif

                            @if ($order->status === 'shipping')
                                <form action="{{ route('admin.orders.shipping', $order->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="admin-button-table" onclick="return confirm('Chuyển sang trạng thái Đang giao?')">
                                        Đang giao hàng
                                    </button>
                                </form>
                            @endif
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Phân trang --}}
    {{ $orders->links() }}
</div>
@endsection
