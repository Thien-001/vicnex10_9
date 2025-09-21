@extends('layouts.layout')

@section('title', 'Danh sách danh mục')

@section('content')
<div class="head-title">
	<div class="left">
		<h1>Danh mục sản phẩm</h1>
		<ul class="breadcrumb">
			<li><a href="#">Danh mục sản phẩm</a></li>
			<li><i class='bx bx-chevron-right'></i></li>
			<li><a class="active" href="#">Danh sách danh mục</a></li>
		</ul>
	</div>
	<a href="{{ route('admin.categories.create') }}" class="btn-download">
		<span class="text">+ Thêm danh mục mới</span>
	</a>
</div>
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<div class="body-content">
	<table>
		<thead>
			<tr>
				<th>STT</th>
				<th>Ảnh</th>
				<th>Tên danh mục</th>
				<th>Mô tả</th>
				<th>Số sản phẩm</th>
				<th>Ngày tạo</th>
				<th>Trạng thái</th>
				<th>Thao Tác</th>
			</tr>
		</thead>
		<tbody>
    @foreach($categories as $index => $category)
        <tr>
            <td>{{ $categories->firstItem() + $index }}</td>
            <td>
                @if($category->Image)
                    <img src="{{ asset($category->Image) }}" width="50" alt="Category Image">
                @else
                    <img src="{{ asset('WebAdmin/img/default.png') }}" width="50" alt="Default Image">
                @endif
            </td>
            <td>{{ $category->Name }}</td>
            <td>{{ $category->Description }}</td>
            <td>{{ $category->product_count }}</td>
            <td>{{ $category->Create_at ? \Carbon\Carbon::parse($category->Create_at)->format('d/m/Y') : '' }}</td>
            <td>
                @if(isset($category->Status) && $category->Status == 1)
                    <span class="badge bg-success">Active</span>
                @else
                    <span class="badge bg-secondary">Inactive</span>
                @endif
            </td>
            <td class="action-buttons">
                <a href="{{ route('admin.categories.edit', $category->Categories_ID) }}" class="btn btn-warning btn-sm" title="Edit"><i class="bx bx-edit"></i></a>
                <form action="{{ route('admin.categories.toggle', $category->Categories_ID) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('PATCH')
                    @if(isset($category->Status) && $category->Status == 1)
                        <button type="submit" class="btn btn-secondary btn-sm" title="Tạm thời ẩn"><i class="bx bx-hide"></i></button>
                    @else
                        <button type="submit" class="btn btn-success btn-sm" title="Hiện lại"><i class="bx bx-show"></i></button>
                    @endif
                </form>
                @if($category->product_count == 0)
                    <form action="{{ route('admin.categories.destroy', $category->Categories_ID) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" title="Delete" onclick="return confirm('Bạn có chắc chắn muốn xóa?')"><i class="bx bx-trash"></i></button>
                    </form>
                @else
                    <button class="btn btn-secondary btn-sm" disabled title="Không thể xóa danh mục còn sản phẩm"><i class="bx bx-block"></i></button>
                @endif
            </td>

        </tr>
    @endforeach
</tbody>

	</table>
    {{ $categories->links() }}
</div>
@endsection
