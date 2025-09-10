@extends('layouts.layout')

@section('content')
<main>
    <div class="head-title">
        <div class="left">
            <h1>Chi Tiết Sản Phẩm</h1>
            <ul class="breadcrumb">
                <li><a href="#">Sản phẩm</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Chi tiết</a></li>
            </ul>
        </div>
        <a href="{{ route('admin.products.index') }}" class="btn-download">
            <span class="text">Quay lại</span>
        </a>
    </div>

    <div style="
        padding: 36px 32px;
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 4px 24px rgba(1,84,185,0.10);
        max-width: 900px;
        margin: 32px auto 0;
        font-size: 17px;
    ">
        @if($product->Thumbnail)
            <div style="text-align: center; margin-bottom: 28px;">
                <img src="{{ asset($product->Thumbnail) }}" alt="Thumbnail" style="max-width: 100%; height: auto; border-radius: 14px; box-shadow: 0 2px 12px rgba(1,84,185,0.08);">
            </div>
        @endif

        <h2 style="margin-bottom: 12px; font-size: 28px; font-weight: 800; color: #0154b9;">{{ $product->Name }}</h2>

        <div style="display: flex; flex-wrap: wrap; gap: 28px; margin-bottom: 18px;">
            <div><strong>Mã sản phẩm:</strong> {{ $product->Product_ID }}</div>
            <div><strong>Danh mục:</strong> {{ $product->category->Name ?? 'N/A' }}</div>
            <div>
                <strong>Trạng thái:</strong>
                <span style="color: {{ $product->Status ? '#1bbf4c' : '#e74c3c' }}; font-weight: 600;">
                    {{ $product->Status ? 'Hiển thị' : 'Ẩn' }}
                </span>
            </div>
            <div><strong>Giá:</strong> {{ number_format($product->Price, 0, ',', '.') }} VNĐ</div>
            <div><strong>Số lượng tổng:</strong> {{ $product->Quantity }}</div>
            <div><strong>Lượt xem:</strong> {{ $product->View ?? 0 }}</div>
            <div><strong>Số biến thể:</strong> {{ $product->variants->count() }}</div>
        </div>

        @if($product->variants && $product->variants->count())
            <div style="margin-top: 24px;">
                <strong>Danh sách biến thể:</strong>
                <table style="width:100%; margin-top:10px; background:#fafdff; border-radius:8px; overflow:hidden;">
                    <thead>
                        <tr style="background:#e3f2fd;">
                            <th style="padding:8px 12px;">#</th>
                            <th style="padding:8px 12px;">Thuộc tính</th>
                            <th style="padding:8px 12px;">Giá</th>
                            <th style="padding:8px 12px;">Số lượng</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($product->variants as $i => $variant)
                            <tr>
                                <td style="padding:8px 12px;">{{ $i+1 }}</td>
                                <td style="padding:8px 12px;">
                                    @if($variant->values && $variant->values->count())
                                        @foreach($variant->values as $value)
                                            <span style="background:#f6f8fc; border-radius:6px; padding:2px 8px; margin-right:6px;">
                                                {{ $value->attribute->Name ?? '' }}: {{ $value->Value }}
                                            </span>
                                        @endforeach
                                    @endif
                                    @if($variant->SKU)
                                        <div style="margin-top:4px; color:#888; font-size:14px;">
                                            <strong>SKU:</strong> {{ $variant->SKU }}
                                        </div>
                                    @endif
                                </td>
                                <td style="padding:8px 12px;">{{ number_format($variant->Price, 0, ',', '.') }} VNĐ</td>
                                <td style="padding:8px 12px;">{{ $variant->Quantity }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        @if($product->Description)
            <div style="margin-top: 24px;">
                <strong>Mô tả:</strong>
                <div style="background-color: #f6f8fc; padding: 14px 18px; border-radius: 8px; margin-top: 6px; color: #444;">
                    {!! $product->Description !!}
                </div>
            </div>
        @endif

        <div style="margin-top: 36px; display: flex; gap: 16px;">
            <a href="{{ route('admin.products.index') }}" 
               class="btn btn-secondary"
               style="background: #e3f2fd; color: #0154b9; border: none; font-weight: 600; padding: 10px 28px; border-radius: 8px; font-size: 16px; transition: background 0.2s;">
                Quay lại danh sách
            </a>
            <a href="{{ route('admin.products.edit', $product->Product_ID) }}" 
               class="btn btn-primary"
               style="background: #0154b9; color: #fff; border: none; font-weight: 600; padding: 10px 28px; border-radius: 8px; font-size: 16px; transition: background 0.2s;">
                Chỉnh sửa
            </a>
        </div>
    </div>
</main>

<style>
    .btn.btn-secondary:hover {
        background: #b6d4fa !important;
        color: #003c7e !important;
    }
    .btn.btn-primary:hover {
        background: #003c7e !important;
        color: #fff !important;
    }
</style>
@endsection