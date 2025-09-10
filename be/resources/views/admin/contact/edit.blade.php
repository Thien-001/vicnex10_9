@extends('layouts.layout')

@section('title', 'Sửa liên hệ')

@section('content')
<div class="container mt-4" style="max-width: 600px;">
    <h2 style="color:#0154b9; font-weight:700; margin-bottom: 28px;">Cập nhật liên hệ khách hàng</h2>
    <form method="POST" action="{{ route('admin.contact.update', $contact->Contact_ID) }}" style="background: #fff; border-radius: 14px; box-shadow: 0 2px 16px rgba(1,84,185,0.07); padding: 28px 24px;">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label fw-bold">Họ tên</label>
            <input type="text" class="form-control" value="{{ $contact->Name }}" disabled>
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">Email</label>
            <input type="text" class="form-control" value="{{ $contact->Email }}" disabled>
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">Điện thoại</label>
            <input type="text" class="form-control" value="{{ $contact->Phone }}" disabled>
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">Chủ đề</label>
            <input type="text" class="form-control" value="{{ $contact->Subject }}" disabled>
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">Nội dung</label>
            <textarea class="form-control" rows="3" disabled>{{ $contact->Message }}</textarea>
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">Trạng thái</label>
            <select class="form-select" name="Status">
                <option value="0" {{ $contact->Status == 0 ? 'selected' : '' }}>Chưa xử lý</option>
                <option value="1" {{ $contact->Status == 1 ? 'selected' : '' }}>Đã xử lý</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">Ghi chú</label>
            <textarea class="form-control" name="Note" rows="2" placeholder="Nhập ghi chú nếu cần...">{{ $contact->Note }}</textarea>
        </div>
        <div class="d-flex justify-content-between align-items-center mt-4">
            <button type="submit" class="btn btn-primary px-4" style="font-weight:600;">Cập nhật</button>
            <a href="{{ route('admin.contact.index') }}" class="btn btn-secondary">Quay lại</a>
        </div>
    </form>
</div>
@endsection