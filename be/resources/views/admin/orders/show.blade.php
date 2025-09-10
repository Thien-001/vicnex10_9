@extends('layouts.layout')

@section('content')
<style>
.order-info.card, .order-details.card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.07);
    padding: 28px 24px;
    margin-bottom: 32px;
}
.order-info .info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 18px;
}
.order-status-badge {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    font-weight: 600;
    padding: 5px 16px;
    border-radius: 18px;
    font-size: 15px;
}
.status-pending { background: #fff7e6; color: #f59e42; }
.status-confirmed { background: #e6f4ff; color: #3b82f6; }
.status-shipping { background: #e0f7fa; color: #009688; }
.status-shipped, .status-completed { background: #e6ffe6; color: #22c55e; }
.status-cancelled { background: #ffe6e6; color: #ef4444; }
.table-order-details {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    border-radius: 8px;
    overflow: hidden;
}
.table-order-details th, .table-order-details td {
    padding: 12px 14px;
    text-align: center;
    border-bottom: 1px solid #f1f5f9;
}
.table-order-details th {
    background: #f3f6fa;
    font-weight: 600;
    color: #0154b9;
    font-size: 15px;
}
.table-order-details tr:hover {
    background: #eaf4ff;
    transition: background 0.2s;
}
@media (max-width: 900px) {
    .order-info .info-grid { grid-template-columns: 1fr; }
    .table-order-details th, .table-order-details td { padding: 8px 6px; font-size: 13px; }
}
</style>

<div class="head-title">
    <div class="left">
        <h1>Chi tiết đơn hàng</h1>
        <ul class="breadcrumb">
            <li><a href="{{ route('admin.orders.index') }}">Đơn hàng</a></li>
            <li><i class='bx bx-chevron-right'></i></li>
            <li><a class="active" href="#">Chi tiết</a></li>
        </ul>
    </div>
    <a href="{{ route('admin.orders.index') }}" class="btn-download">
        <span class="text">Quay lại</span>
    </a>
</div>

{{-- Thông tin đơn hàng --}}
<div class="order-info card">
    <h3 style="margin-bottom: 20px;">🧾 Thông tin đơn hàng</h3>
    <table style="width:100%;border-collapse:collapse;">
        <tbody>
            <tr>
                <td style="font-weight:600;width:180px;">Mã đơn hàng</td>
                <td>{{ $order->order_code ?? $order->id }}</td>
            </tr>
            <tr>
                <td style="font-weight:600;">Tên khách hàng</td>
                <td>{{ $order->user->Name ?? $order->full_name ?? 'Ẩn danh' }}</td>
            </tr>
            <tr>
                <td style="font-weight:600;">Địa chỉ giao hàng</td>
                <td>{{ $order->shipping_address ?? $order->address }}</td>
            </tr>
            <tr>
                <td style="font-weight:600;">Phí vận chuyển</td>
                <td>{{ number_format($order->shipping_fee ?? $order->shiping_fee, 0, ',', '.') }}₫</td>
            </tr>
            <tr>
                <td style="font-weight:600;">Tổng tiền</td>
                <td><span style="color: #d32f2f">{{ number_format($order->total_price ?? $order->total_amount, 0, ',', '.') }}₫</span></td>
            </tr>
            <tr>
                <td style="font-weight:600;">Trạng thái</td>
                <td>
                    @switch($order->status)
                        @case('pending')
                            <span class="order-status-badge status-pending"><i class="fas fa-clock"></i> Chờ xử lý</span>
                            @break
                        @case('confirmed')
                            <span class="order-status-badge status-confirmed"><i class="fas fa-check-circle"></i> Đã xác nhận</span>
                            @break
                        @case('shipping')
                            <span class="order-status-badge status-shipping"><i class="fas fa-truck"></i> Đang giao</span>
                            @break
                        @case('shipped')
                            <span class="order-status-badge status-shipped"><i class="fas fa-box"></i> Đã giao</span>
                            @break
                        @case('completed')
                            <span class="order-status-badge status-completed"><i class="fas fa-star"></i> Hoàn thành</span>
                            @break
                        @case('cancelled')
                            <span class="order-status-badge status-cancelled"><i class="fas fa-times-circle"></i> Đã hủy</span>
                            @break
                        @default
                            <span class="order-status-badge">Không rõ</span>
                    @endswitch
                </td>
            </tr>
        </tbody>
    </table>
</div>

{{-- Thông tin khách hàng --}}
<div class="order-info card">
    <h3 style="margin-bottom: 20px;">👤 Thông tin khách hàng</h3>
    <div class="info-grid">
        <div>
            <p><strong>Họ tên:</strong> {{ $order->full_name ?? ($order->user->Name ?? 'Ẩn danh') }}</p>
            <p><strong>Email:</strong> {{ $order->email ?? ($order->user->Email ?? '') }}</p>
            <p><strong>Số điện thoại:</strong> {{ $order->phone ?? ($order->user->Phone ?? '---') }}</p>
            <p><strong>Ghi chú:</strong> {{ $order->note ?? 'Không có' }}</p>
            <p><strong>Voucher:</strong> {{ $order->voucher_id ?? 'Không có' }}</p>
        </div>
        <div>
            <p><strong>Địa chỉ:</strong> {{ $order->address ?? $order->shipping_address }}</p>
            <p><strong>Tỉnh/TP:</strong> {{ $order->province_name ?? $order->province_code }}</p>
            <p><strong>Quận/Huyện:</strong> {{ $order->district_name ?? $order->district_code }}</p>
            <p><strong>Phường/Xã:</strong> {{ $order->ward_name ?? $order->ward_code }}</p>
            <p><strong>Phương thức thanh toán:</strong> {{ ucfirst($order->payment_method) }}</p>
        </div>
    </div>
</div>

{{-- Sản phẩm trong đơn hàng --}}
<div class="order-products card" style="margin-top:32px;">
    <h3 style="margin-bottom: 20px;">📦 Sản phẩm trong đơn hàng</h3>
    <table class="table-order-details" style="width:100%;border-collapse:collapse;">
        <thead>
            <tr>
                <th style="padding:12px;">Ảnh</th>
                <th style="padding:12px;">Tên sản phẩm</th>
                <th style="padding:12px;">SKU</th>
                <th style="padding:12px;">Giá</th>
                <th style="padding:12px;">Số lượng</th>
                <th style="padding:12px;">Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->details as $detail)
                <tr>
                    <td style="padding:12px;">
                        <img src="{{ $detail->product->Image ?? '/img/no-image.png' }}"
                             alt="{{ $detail->product->Name ?? $detail->product_name ?? '---' }}"
                             style="width:60px;height:60px;object-fit:cover;border-radius:8px;box-shadow:0 2px 8px rgba(1,84,185,0.08);background:#f3f6fa;">
                    </td>
                    <td style="padding:12px;font-weight:500;">
                        {{ $detail->product->Name ?? $detail->product_name ?? '---' }}
                    </td>
                    <td style="padding:12px;color:#0154b9;">
                        {{ $detail->product->SKU ?? $detail->SKU ?? '---' }}
                    </td>
                    <td style="padding:12px;">
                        <span style="color:#009688;font-weight:600;">
                            {{ number_format($detail->price, 0, ',', '.') }}₫
                        </span>
                    </td>
                    <td style="padding:12px;">{{ $detail->quantity }}</td>
                    <td style="padding:12px;font-weight:700;color:#d32f2f;">
                        {{ number_format($detail->price * $detail->quantity, 0, ',', '.') }}₫
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
