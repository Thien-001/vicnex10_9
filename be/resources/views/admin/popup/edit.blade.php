@extends('layouts.layout')
@section('content')
<style>
.popup-form-container {
    max-width: 1000px;
    margin: 36px auto;
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 4px 24px rgba(1,84,185,0.08);
    padding: 32px 28px;
}
.popup-form-container h2 {
    color: #0154b9;
    font-weight: 700;
    margin-bottom: 28px;
    text-align: center;
}
.popup-form-container .btn-success {
    background: linear-gradient(90deg,#0154b9 0%,#3bb2ff 100%);
    border: none;
    border-radius: 8px;
    font-weight: 700;
    padding: 10px 28px;
    font-size: 16px;
    margin-bottom: 18px;
    box-shadow: 0 2px 8px rgba(1,84,185,0.12);
    transition: background 0.2s;
}
.popup-form-container .btn-success:hover {
    background: linear-gradient(90deg,#3bb2ff 0%,#0154b9 100%);
}
.popup-form-container .btn-secondary {
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
.popup-form-container .btn-secondary:hover {
    background: #4a65d1;
    /* color: #003c7a; */
}
#content{
    width: 100%;
    left: 0;
}
</style>
<div class="popup-form-container">
    <h2>Sửa Popup</h2>
    <form action="{{ route('admin.popup.update', $popup->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="image_url" class="form-label">Ảnh</label>
            <div class="mb-2">
                @if($popup->image_url)
                    <img id="preview-image" src="{{ asset($popup->image_url) }}" alt="Popup" width="120">
                @else
                    <img id="preview-image" src="" alt="Popup" style="display:none;" width="120">
                @endif
            </div>
            <input type="file" class="form-control" id="image_url" name="image_url" accept="image/*" onchange="previewImage(event)">
        </div>
        <div class="mb-3">
            <label for="title" class="form-label">Tiêu đề</label>
            <input type="text" class="form-control" id="title" name="title" value="{{ $popup->title }}">
        </div>
        <div class="mb-3">
            <label for="content" class="form-label">Nội dung</label>
            <textarea class="form-control" id="content" name="content" rows="3">{{ $popup->content }}</textarea>
        </div>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" {{ $popup->is_active ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">
                Hiển thị popup
            </label>
        </div>
        <div class="form-actions">
            <button type="submit">Cập nhật</button>
            <a href="{{ route('admin.popup.index') }}" class="btn btn-secondary">Quay lại</a>

        </div>
        {{-- <button type="submit" class="btn btn-success">Cập nhật</button>
        <a href="{{ route('admin.popup.index') }}" class="btn btn-secondary">Quay lại</a> --}}
    </form>
</div>
<script>
function previewImage(event) {
    const input = event.target;
    const preview = document.getElementById('preview-image');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
