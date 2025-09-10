@extends('layouts.layout')
@section('content')
<style>
/* ...css giữ nguyên... */
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
                    <img id="preview-image" src="{{ asset('storage/' . $popup->image_url) }}" alt="Popup" width="120">
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
        <button type="submit" class="btn btn-success">Cập nhật</button>
        <a href="{{ route('admin.popup.index') }}" class="btn btn-secondary">Quay lại</a>
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