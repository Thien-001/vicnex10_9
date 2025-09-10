@extends('layouts.layout')

@section('title', 'Thêm lịch đặt sân')

@section('content')
<div class="head-title">
        <div class="left">
            <h1>Thêm lịch dặt sân</h1>
            <ul class="breadcrumb">
                <li><a href="#">Quản lí sân</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Thêm lịch dặt sân</a></li>
            </ul>
        </div>
        <a href="{{ route('admin.bookings.index') }}" class="btn-download">
            <span class="text">Quay lại</span>
	</a>
    </div>

<div class="form-add">
    <h2>Thêm Lịch Đặt Sân</h2>

    @if ($errors->any())
        <div style="background: #fee2e2; padding: 10px; border-radius: 6px; margin-bottom: 16px; color: #b91c1c;">
            <ul style="padding-left: 18px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.bookings.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="customer_name">Tên khách hàng (hiển thị)</label>
            <input type="text" name="customer_name" id="customer_name" value="{{ old('customer_name') }}" placeholder="Nguyễn Văn A">
            @error('customer_name') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label for="user_id">Tài khoản khách hàng (user)</label>
            <select name="user_id" id="user_id">
                <option value="">-- Không liên kết tài khoản --</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }} (ID: {{ $user->id }})
                    </option>
                @endforeach
            </select>
            @error('user_id') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label for="courts_id">Chọn sân</label>
            <select name="courts_id" id="courts_id" required>
                <option value="">-- Chọn sân --</option>
                @foreach($courts as $court)
                    <option value="{{ $court->Courts_ID }}" {{ old('courts_id') == $court->Courts_ID ? 'selected' : '' }}>
                        {{ $court->Name }} - {{ $court->Location }}
                    </option>
                @endforeach
            </select>
            @error('courts_id') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label for="booking_date">Ngày đặt</label>
            <input type="date" name="booking_date" id="booking_date" value="{{ old('booking_date') }}" required>
            @error('booking_date') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label for="start_time">Giờ bắt đầu</label>
            <input type="time" name="start_time" id="start_time" value="{{ old('start_time') }}" required>
            @error('start_time') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label for="end_time">Giờ kết thúc</label>
            <input type="time" name="end_time" id="end_time" value="{{ old('end_time') }}" required>
            @error('end_time') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label for="price_per_hour">Giá / giờ (VNĐ)</label>
            <input type="number" name="price_per_hour" id="price_per_hour" value="{{ old('price_per_hour') }}" required>
            @error('price_per_hour') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label for="status">Trạng thái</label>
            <select name="status" id="status">
                <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Hoạt động</option>
                <option value="cancelled" {{ old('status') === 'cancelled' ? 'selected' : '' }}>Đã huỷ</option>
            </select>
            @error('status') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label for="note">Ghi chú (tuỳ chọn)</label>
            <input type="text" name="note" id="note" value="{{ old('note') }}">
        </div>

        <div class="form-actions">
            <button type="submit">Thêm lịch đặt</button>
        </div>
    </form>
</div>
@endsection
