@extends('layouts.layout')

@section('title', 'Lịch đặt sân')

@section('content')
<div class="head-title">
				<div class="left">
					<h1>Lịch đặt sân</h1>
					<ul class="breadcrumb">
						<li>
							<a href="#">Quản lí sân</a>
						</li>
						<li><i class='bx bx-chevron-right' ></i></li>
						<li>
							<a class="active" href="#">Danh sách đặt sân</a>
						</li>
					</ul>
				</div>
				<a href="{{ route('admin.bookings.create') }}" class="btn-download">
                    <span class="text">+ Thêm lịch đặt sân mới</span>
                </a>
			</div>
@if(session('success'))
        <div class="alert alert-success" style="margin: 15px 0;">{{ session('success') }}</div>
    @endif
<div class="body-content">


    <table>
        <thead>
            <tr>
                <th>STT</th>
                <th>Khách hàng</th>
                <th>Sân</th>
                <th>Ngày</th>
                <th>Giờ</th>
                <th>Tổng tiền</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($bookings as $index => $booking)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $booking->user->Name ?? $booking->customer_name }}</td>
                    <td>{{ $booking->court->Name ?? 'Không rõ' }}</td>
                    <td>{{ $booking->Booking_date }}</td>
                    <td>{{ $booking->Start_time }} - {{ $booking->End_time }}</td>
                    <td>{{ number_format($booking->Total_price) }}đ</td>
                    <td>
                        @if($booking->Status === 1)
                            <span class="status-active">Đã xác nhận</span>
                        @elseif($booking->Status === 0)
                            <span class="status-pending">Chờ xác nhận</span>
                        @elseif($booking->Status === -1)
                            <span class="status-cancelled">Đã huỷ</span>
                        @endif
                    </td>
                    <td>
                        <div class="action-buttons">
                            <!-- Nút Sửa -->
                            <button class="admin-button-table">
                                <a href="{{ route('admin.bookings.edit', $booking) }}" style="display:block; width:100%; height:100%; color:inherit; text-decoration:none;">Sửa</a>
                            </button>

                            <!-- Nút Xác nhận (luôn hiển thị) -->
                            <form action="{{ route('admin.bookings.confirm', $booking) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="admin-button-table" style="background:#e0fce0; color:#22c55e; border:1.5px solid #22c55e; margin-left:4px;"
                                    onclick="return confirm('Xác nhận lịch đặt sân này?')">
                                    Xác nhận
                                </button>
                            </form>

                            <!-- Nút Xóa -->
                            <form action="{{ route('admin.bookings.destroy', $booking) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="admin-button-table btn-delete" onclick="return confirm('Bạn có chắc muốn xoá?')">Xoá</button>
                            </form>
                        </div>
                    </td>

                </tr>
            @empty
                <tr>
                    <td colspan="8">Không có lịch đặt sân nào.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    {{ $bookings->links() }}
</div>
@endsection
