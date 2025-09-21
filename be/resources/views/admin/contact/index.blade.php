@extends('layouts.layout')

@section('title', 'Quản lý liên hệ')

@section('content')
<style>
    .mt-4{
        width: 100%;
    }
    .table-contact-list th, .table-contact-list td {
        padding: 10px 8px;
        vertical-align: middle;
    }
    .table-contact-list th {
        background: #f3f4f6;
        font-weight: 600;
        color: #222;
    }
    .table-contact-list tr:nth-child(even) {
        background: #fafbfc;
    }
    .action-buttons {
        /* display: flex; */
        gap: 8px;
        align-items: center;
    }
    .admin-button-table {
        background: #2563eb;
        color: #fff;
        border: none;
        border-radius: 5px;
        padding: 6px 14px;
        font-size: 15px;
        cursor: pointer;
        transition: background 0.2s;
    }
    .admin-button-table.btn-delete {
        background: #e11d48;
    }
    .admin-button-table:hover {
        background: #1d4ed8;
    }
    .admin-button-table.btn-delete:hover {
        background: #be123c;
    }
</style>

<div class="mt-4">
    <div class="head-title">
        <div class="left">
            <h1>Liên hệ</h1>
            <ul class="breadcrumb">
                <li><a href="#">Liên hệ</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Danh sách liên hệ</a></li>
            </ul>
        </div>
    </div>
    @if(session('success'))
        <div class="alert alert-success" style="margin: 15px 0;">{{ session('success') }}</div>
    @endif

    <div class="body-content">
        <table class="table-contact-list" width="100%" border="0" cellspacing="0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Họ tên</th>
                    <th>Email</th>
                    <th>Điện thoại</th>
                    <th>Chủ đề</th>
                    <th>Nội dung</th>
                    <th>Trạng thái</th>
                    <th>Ghi chú</th>
                    <th>Ngày gửi</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($contacts as $i => $contact)
                <tr>
                    <td>{{ $i + 1 + ($contacts->currentPage() - 1) * $contacts->perPage() }}</td>
                    <td>{{ $contact->Name }}</td>
                    <td>{{ $contact->Email }}</td>
                    <td>{{ $contact->Phone }}</td>
                    <td>{{ $contact->Subject }}</td>
                    <td>{{ $contact->Message }}</td>
                    <td>
                        @if($contact->Status == 1)
                            <span style="color:#22c55e;font-weight:500;">Đã xử lý</span>
                        @else
                            <span style="color:#ef4444;font-weight:500;">Chưa xử lý</span>
                        @endif
                    </td>
                    <td>{{ $contact->Note }}</td>
                    <td>{{ \Carbon\Carbon::parse($contact->Created_at)->format('d/m/Y H:i') }}</td>
                    <td class="action-buttons">
                        <a href="{{ route('admin.contact.edit', $contact->Contact_ID) }}" class="admin-button-table">cập nhật</a>
                        {{-- <form action="{{ route('admin.contact.destroy', $contact->Contact_ID) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="admin-button-table btn-delete" onclick="return confirm('Xóa liên hệ này?')">Xóa</button>
                        </form> --}}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center text-muted">Chưa có liên hệ nào.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <!-- Phân trang -->
        {{ $contacts->links() }}
    </div>
</div>
@endsection
