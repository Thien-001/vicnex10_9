@extends('layouts.layout')

@section('content')
<div class="container py-4">
    <h2 class="mb-4 text-primary">Gán thuộc tính cho danh mục</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.category-attribute.store') }}" method="POST" class="border p-4 rounded shadow-sm bg-white">
        @csrf

        <div class="mb-3">
            <label for="category" class="form-label fw-bold">Chọn danh mục:</label>
            <select name="category_id" id="category" class="form-select" required>
                <option value="">-- Chọn danh mục --</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->Categories_ID }}">{{ $category->Name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Chọn thuộc tính:</label>
            <div class="row">
                @foreach ($attributes as $attribute)
                    <div class="col-md-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="attribute_ids[]" value="{{ $attribute->Attributes_ID }}" id="attr{{ $attribute->Attributes_ID }}">
                            <label class="form-check-label" for="attr{{ $attribute->Attributes_ID }}">
                                {{ $attribute->Name }}
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Lưu thuộc tính</button>
    </form>
</div>
@endsection
