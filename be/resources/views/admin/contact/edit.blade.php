@extends('layouts.layout')

@section('title', 'Sửa liên hệ')

@section('content')
<style>
    .contact-form-container {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 24px rgba(1, 84, 185, 0.08);
        padding: 32px 28px;
        transition: 0.3s ease-in-out;
    }
    .contact-form-container:hover {
        box-shadow: 0 6px 28px rgba(1, 84, 185, 0.12);
    }
    .contact-form-container h2 {
        font-weight: 700;
        margin-bottom: 28px;
        /* color: #0154b9; */
        /* font-size: 22px; */
        border-bottom: 2px solid #f0f4fa;
        padding-bottom: 12px;
        text-align: center;
    }
    .contact-form-container .form-label {
        font-weight: 600;
        color: #333;
        margin-bottom: 6px;
    }
    .contact-form-container .form-control,
    .contact-form-container .form-select {
        border-radius: 10px;
        padding: 10px 14px;
        border: 1px solid #d6d9e0;
        transition: all 0.25s ease;
    }
    .contact-form-container .form-control:focus,
    .contact-form-container .form-select:focus {
        border-color: #0154b9;
        box-shadow: 0 0 0 0.2rem rgba(1, 84, 185, 0.15);
    }
    .contact-form-container textarea {
        resize: none;
    }
    .contact-form-container .btn-primary {
        background: #0154b9;
        border: none;
        font-weight: 600;
        padding: 10px 28px;
        border-radius: 12px;
        transition: background 0.3s ease;
    }
    .contact-form-container .btn-primary:hover {
        background: #01408f;
    }
.btn-secondary {
    width: 100%;
    background-color: #3498db;
    color: white;
    padding: 10px 20px;
    font-size: 15px;
    border: none;
    margin-top: 10px;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}
.btn-secondary:hover {
    background: #4a65d1;
    /* color: #003c7a; */
}
</style>

<div class="container mt-4">
    <form method="POST" action="{{ route('admin.contact.update', $contact->Contact_ID) }}" class="contact-form-container">
        @csrf
        @method('PUT')
        <h2>Cập nhật liên hệ khách hàng</h2>
        <div class="mb-3">
            <label class="form-label">Họ tên</label>
            <input type="text" class="form-control" value="{{ $contact->Name }}" disabled>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="text" class="form-control" value="{{ $contact->Email }}" disabled>
        </div>
        <div class="mb-3">
            <label class="form-label">Điện thoại</label>
            <input type="text" class="form-control" value="{{ $contact->Phone }}" disabled>
        </div>
        <div class="mb-3">
            <label class="form-label">Chủ đề</label>
            <input type="text" class="form-control" value="{{ $contact->Subject }}" disabled>
        </div>
        <div class="mb-3">
            <label class="form-label">Nội dung</label>
            <textarea class="form-control" rows="3" disabled>{{ $contact->Message }}</textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Trạng thái</label>
            <select class="form-select" name="Status">
                <option value="0" {{ $contact->Status == 0 ? 'selected' : '' }}>Chưa xử lý</option>
                <option value="1" {{ $contact->Status == 1 ? 'selected' : '' }}>Đã xử lý</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Ghi chú</label>
            <textarea class="form-control" name="Note" rows="2" placeholder="Nhập ghi chú nếu cần...">{{ $contact->Note }}</textarea>
        </div>
        <div class="form-actions">
            <button type="submit">Cập nhật</button>
            <a href="{{ route('admin.contact.index') }}" class="btn btn-secondary">Quay lại</a>

        </div>
        {{-- <div class="d-flex justify-content-between align-items-center mt-4">
            <button type="submit" class="btn btn-primary">Cập nhật</button>
            <a href="{{ route('admin.contact.index') }}" class="btn btn-secondary">Quay lại</a>
        </div> --}}
    </form>
</div>
@endsection
