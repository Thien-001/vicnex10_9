@extends('layouts.layout')
@section('title', 'Danh sách đơn hàng')

@section('content')
<style>
/* Phần form lọc tổng thể - đồng bộ với trang product */
.filter-form {
    background-color: #fdfdfd;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    font-size: 14px;
    border: 1px solid #e5e7eb;
    margin-bottom: 20px;
}

/* Container sử dụng flex */
.filter-form > div {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    align-items: flex-end;
}

/* Nhóm label + input/select */
.filter-form div > label {
    display: block;
    font-weight: 500;
    margin-bottom: 5px;
    color: #333;
    font-size: 13px;
}

.filter-form input[type="text"],
.filter-form input[type="number"],
.filter-form input[type="date"],
.filter-form select {
    width: 200px;
    padding: 8px 12px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 14px;
    background-color: #fff;
    transition: all 0.2s ease;
}

.filter-form input[type="text"]:focus,
.filter-form input[type="number"]:focus,
.filter-form input[type="date"]:focus,
.filter-form select:focus {
    border-color: #3b82f6;
    outline: none;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* Nút thao tác */
.filter-actions {
    display: flex;
    gap: 10px;
    align-items: center;
}

.admin-form-loc {
    background: #3b82f6;
    color: #ffffff;
    border: none;
    border-radius: 6px;
    padding: 8px 16px;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
    display: inline-block;
    font-weight: 500;
    text-align: center;
}

.admin-form-loc:hover {
    background: #2563eb;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    color: #ffffff;
    text-decoration: none;
}

/* Nút đặt lại */
.reset-btn {
    background: #6b7280 !important;
    color: #ffffff !important;
}

.reset-btn:hover {
    background: #4b5563 !important;
    color: #ffffff !important;
}

/* Nút xuất Excel */
.export-btn {
    background: #10b981 !important;
    color: #ffffff !important;
}

.export-btn:hover {
    background: #059669 !important;
    color: #ffffff !important;
}

/* Thống kê kết quả tìm kiếm */
.search-results-info {
    background: #f0f9ff;
    border: 1px solid #bae6fd;
    border-radius: 8px;
    padding: 12px 16px;
    margin-bottom: 20px;
    font-size: 14px;
    color: #0369a1;
}

.search-results-info strong {
    color: #0c4a6e;
}

/* Responsive */
@media (max-width: 768px) {
    .filter-form > div {
        flex-direction: column;
        align-items: stretch;
    }

    .filter-form div > div {
        width: 100%;
        margin-bottom: 15px;
    }

    .filter-form input[type="text"],
    .filter-form input[type="number"],
    .filter-form input[type="date"],
    .filter-form select {
        width: 100%;
    }

    .filter-actions {
        justify-content: center;
        flex-wrap: wrap;
    }
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
    <form action="{{ route('admin.orders.index') }}" method="GET" class="filter-form">
        <div>
            <!-- Tìm kiếm theo mã đơn hàng -->
            <div>
                <label for="order_code">Mã đơn hàng:</label>
                <input type="text" name="order_code" id="order_code" value="{{ request('order_code') }}" placeholder="VD: 1, DH000001, hoặc mã đơn hàng...">
            </div>

            <!-- Tìm kiếm theo tên khách hàng -->
            <div>
                <label for="customer_name">Tên khách hàng:</label>
                <input type="text" name="customer_name" id="customer_name" value="{{ request('customer_name') }}" placeholder="Nhập tên khách hàng...">
            </div>

            <!-- Tìm kiếm theo số điện thoại -->
            <div>
                <label for="phone">Số điện thoại:</label>
                <input type="text" name="phone" id="phone" value="{{ request('phone') }}" placeholder="Nhập số điện thoại...">
            </div>

            <!-- Lọc theo trạng thái -->
            <div>
                <label for="status">Trạng thái:</label>
                <select name="status" id="status">
                    <option value="">Tất cả trạng thái</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                    <option value="shipping" {{ request('status') == 'shipping' ? 'selected' : '' }}>Đang giao hàng</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                </select>
            </div>

            <!-- Lọc theo phương thức thanh toán -->
            <div>
                <label for="payment_method">Thanh toán:</label>
                <select name="payment_method" id="payment_method">
                    <option value="">Tất cả phương thức</option>
                    <option value="cod" {{ request('payment_method') == 'cod' ? 'selected' : '' }}>Thanh toán khi nhận hàng</option>
                    <option value="banking" {{ request('payment_method') == 'banking' ? 'selected' : '' }}>Chuyển khoản</option>
                    <option value="vnpay" {{ request('payment_method') == 'vnpay' ? 'selected' : '' }}>VNPay</option>
                    <option value="momo" {{ request('payment_method') == 'momo' ? 'selected' : '' }}>MoMo</option>
                </select>
            </div>

            <!-- Lọc theo khoảng giá -->
            <div>
                <label for="min_amount">Từ (VNĐ):</label>
                <input type="number" name="min_amount" id="min_amount" value="{{ request('min_amount') }}" placeholder="0" min="0">
            </div>

            <div>
                <label for="max_amount">Đến (VNĐ):</label>
                <input type="number" name="max_amount" id="max_amount" value="{{ request('max_amount') }}" placeholder="10000000" min="0">
            </div>

            <!-- Lọc theo ngày -->
            <div>
                <label for="date_from">Từ ngày:</label>
                <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}">
            </div>

            <div>
                <label for="date_to">Đến ngày:</label>
                <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}">
            </div>

            <!-- Nút thao tác -->
            <div class="filter-actions">
                <button type="submit" class="admin-form-loc">
                    Lọc
                </button>
                <a href="{{ route('admin.orders.index') }}" class="admin-form-loc reset-btn">
                    Đặt lại
                </a>
                <button type="button" onclick="exportOrders()" class="admin-form-loc export-btn">
                    Xuất Excel
                </button>
            </div>
        </div>
    </form>

    <!-- Hiển thị số lượng kết quả tìm kiếm -->
    @if($orders->count() > 0)
        <div class="search-results-info">
            <strong>Tìm thấy {{ $orders->total() }} đơn hàng</strong>
            @if(request()->hasAny(['order_code', 'customer_name', 'phone', 'status', 'payment_method', 'min_amount', 'max_amount', 'date_from', 'date_to']))
                phù hợp với bộ lọc của bạn
            @endif
        </div>
    @endif

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
                        <strong style="color:#0051ff;font-size:15px;">DH{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</strong>
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
                        {{ number_format($order->total_price ?? $order->total_amount, 0, ',', '.') }}₫
                        <div style="font-size:13px;color:#888;">
                            Phí ship: {{ number_format($order->shipping_fee ?? $order->shiping_fee, 0, ',', '.') }}₫<br>
                            <span style="color:#0154b9;">
                                Tổng thanh toán:
                                {{ number_format(($order->total_price ?? $order->total_amount) + ($order->shipping_fee ?? $order->shiping_fee), 0, ',', '.') }}₫
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

<script>
// Hàm xuất Excel
function exportOrders() {
    // Lấy tất cả các tham số filter hiện tại
    const params = new URLSearchParams(window.location.search);
    params.set('export', 'excel');

    // Tạo URL với các tham số filter
    const exportUrl = '{{ route("admin.orders.index") }}?' + params.toString();

    // Mở link xuất file
    window.open(exportUrl, '_blank');
}

// Auto-submit form khi thay đổi select
document.addEventListener('DOMContentLoaded', function() {
    const selectElements = document.querySelectorAll('select[name="status"], select[name="payment_method"]');

    selectElements.forEach(function(select) {
        select.addEventListener('change', function() {
            // Tự động submit form khi thay đổi trạng thái hoặc phương thức thanh toán
            this.form.submit();
        });
    });

    // Thêm placeholder cho input date
    const dateInputs = document.querySelectorAll('input[type="date"]');
    dateInputs.forEach(function(input) {
        if (!input.value) {
            input.placeholder = 'Chọn ngày...';
        }
    });
});

</script>

@endsection
