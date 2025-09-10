@extends('layouts.layout')

@section('content')

<!-- Nhúng trực tiếp Bootstrap CSS nếu layout chưa có -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    .table-hover tbody tr:hover {
        background-color: #f1f3f5;
        transition: background 0.2s;
    }
    .modal-content {
        border-radius: 10px;
    }
    .modal-header {
        background: #0d6efd;
        color: #fff;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
    }
    .modal-title {
        font-weight: bold;
    }
    .btn-close {
        filter: invert(1);
    }
    .admin-button-table.btn-delete {
        background: #dc3545;
        color: #fff;
        border: none;
        padding: 4px 12px;
        border-radius: 4px;
        font-size: 14px;
        transition: background 0.2s;
    }
    .admin-button-table.btn-delete:hover {
        background: #b52a37;
        color: #fff;
    }
    a{
        text-decoration: none;
    }
</style>

<div class="head-title">
    <div class="left">
        <h1>Đánh giá sản phẩm</h1>
        <ul class="breadcrumb">
            <li><a href="#">Đánh giá</a></li>
            <li><i class='bx bx-chevron-right'></i></li>
            <li><a class="active" href="#">Danh sách</a></li>
        </ul>
    </div>
</div>
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="body-content">
    <table class="table table-hover align-middle">
        <thead class="table-primary">
            <tr>
                <th>STT</th>
                <th>Người dùng</th>
                <th>Nội dung</th>
                <th>Sản phẩm</th>
                <th>Ngày giờ</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reviews as $index => $review)
            <tr class="review-row"
                style="cursor:pointer"
                data-username="{{ $review->user->Name ?? 'N/A' }}"
                data-content="{{ $review->Content }}"
                data-product="{{ $review->product->Name ?? 'N/A' }}"
                data-time="{{ $review->Create_at }}">
                <td>{{ $index + 1 }}</td>
                <td>{{ $review->user->Name ?? 'N/A' }}</td>
                <td>{{ $review->Content }}</td>
                <td>{{ $review->product->Name ?? 'N/A' }}</td>
                <td>{{ $review->Create_at }}</td>
                <td>
                    <form action="{{ route('admin.comments.product.destroy', $review->Comment_ID) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="admin-button-table btn-delete" onclick="event.stopPropagation(); return confirm('Bạn chắc chắn muốn xóa?')">Xóa</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $reviews->links() }}
</div>

<!-- Modal chi tiết bình luận -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="detailModalLabel">Chi tiết bình luận sản phẩm</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
      </div>
      <div class="modal-body">
        <p><strong>Người dùng:</strong> <span id="modal-username"></span></p>
        <p><strong>Sản phẩm:</strong> <span id="modal-product"></span></p>
        <p><strong>Nội dung:</strong> <span id="modal-content"></span></p>
        <p><strong>Ngày giờ:</strong> <span id="modal-time"></span></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var detailModalEl = document.getElementById('detailModal');
    var detailModal = new bootstrap.Modal(detailModalEl);

    document.querySelectorAll('.review-row').forEach(function(row) {
        row.addEventListener('click', function (e) {
            // Không mở modal khi click vào nút Xóa
            if (e.target.closest('form')) return;
            document.getElementById('modal-username').textContent = row.getAttribute('data-username');
            document.getElementById('modal-product').textContent = row.getAttribute('data-product');
            document.getElementById('modal-content').textContent = row.getAttribute('data-content');
            document.getElementById('modal-time').textContent = row.getAttribute('data-time');
            detailModal.show();
        });
    });
});
</script>
@endpush
