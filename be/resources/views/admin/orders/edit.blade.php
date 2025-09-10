@extends('layouts.layout')

@section('content')
<div class="head-title">
    <div class="left">
        <h1>Chỉnh sửa đơn hàng</h1>
        <ul class="breadcrumb">
            <li><a href="#">Đơn hàng</a></li>
            <li><i class='bx bx-chevron-right'></i></li>
            <li><a class="active" href="#">Chỉnh sửa</a></li>
        </ul>
    </div>
    <a href="{{ route('admin.orders.index') }}" class="btn-download">
            <span class="text">Quay lại</span></a>
</div>

<div class="form-add">
    <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Mã đơn hàng:</label>
            <input type="text" value="{{ $order->order_code }}" disabled>
        </div>

        <div class="form-group">
            <label>Khách hàng:</label>
            <input type="text" value="{{ $order->user->Name ?? 'Ẩn danh' }}" disabled>
        </div>

        <div class="form-group">
            <label for="shipping_address">Địa chỉ giao hàng:</label>
            <input type="text" name="shipping_address" id="shipping_address" value="{{ old('shipping_address', $order->shipping_address) }}" required>
        </div>

        <div class="form-group">
            <label for="note_user">Ghi chú:</label>
            <textarea name="note_user" id="note_user" rows="3">{{ old('note_user', $order->note_user) }}</textarea>
        </div>

        <div class="form-group">
            <label for="payment_method">Phương thức thanh toán:</label>
            <select name="payment_method" id="payment_method" required>
                @foreach (['COD', 'Bank Transfer', 'Momo', 'ZaloPay'] as $method)
                    <option value="{{ $method }}" {{ $order->payment_method == $method ? 'selected' : '' }}>
                        {{ $method }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="shiping_fee">Phí vận chuyển:</label>
            <input type="number" name="shiping_fee" id="shiping_fee" value="{{ old('shiping_fee', $order->shiping_fee) }}" min="0">
        </div>

        @php
    $statusOptions = [];

    if ($order->status === 'pending') {
        $statusOptions = [
            'shipping' => 'Đang giao',
            'cancelled' => 'Đã hủy',
        ];
    } elseif ($order->status === 'shipping') {
        $statusOptions = [
            'completed' => 'Hoàn thành',
        ];
    }
@endphp

@if (!empty($statusOptions))
    <div class="form-group">
        <label for="status">Trạng thái:</label>
        <select name="status" id="status" required>
            @foreach ($statusOptions as $key => $label)
                <option value="{{ $key }}" {{ $order->status == $key ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>
@else
    <div class="form-group">
        <label>Trạng thái:</label>
        @php
            $statusText = [
                'pending' => 'Chờ xử lý',
                'shipping' => 'Đang giao',
                'completed' => 'Hoàn thành',
                'cancelled' => 'Đã hủy',
                'confirmed' => 'Đã xác nhận', // Thêm nếu có trạng thái này
            ];
        @endphp
        <p><strong>{{ $statusText[$order->status] ?? $order->status }}</strong></p>
    </div>
@endif


        {{-- <div class="form-group">
            <label for="status_method">Tình trạng xử lý:</label>
            <input type="text" name="status_method" id="status_method" value="{{ old('status_method', $order->status_method) }}">
        </div> --}}

        <div class="form-actions">
            <button type="submit">Cập nhật</button>
            {{-- <a href="{{ route('admin.orders.index') }}" style="margin-left: 10px;">Quay lại</a> --}}
        </div>
    </form>
</div>
@endsection
