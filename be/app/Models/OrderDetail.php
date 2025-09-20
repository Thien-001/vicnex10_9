<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderDetail extends Model
{
    protected $table = 'order_detail'; // Sửa lại tên bảng cho đúng
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'Product_ID',
        'product_name',
        'product_image', // THÊM DÒNG NÀY
        'SKU',
        'price',
        'quantity',
        'total',
        'created_at',
        'variant_id', // THÊM DÒNG NÀY
    ];

    protected $casts = [
        'price'      => 'float',
        'total'      => 'float',
        'quantity'   => 'integer',
        'created_at' => 'datetime',
        'variant_id' => 'integer', // THÊM DÒNG NÀY
    ];

    // Chi tiết đơn hàng thuộc về 1 đơn hàng
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    // Chi tiết đơn hàng thuộc về 1 sản phẩm
    public function product()
    {
        return $this->belongsTo(Product::class, 'Product_ID', 'Product_ID');
    }
}

// XÓA toàn bộ đoạn foreach xử lý $request ở dưới!
// Đoạn này phải đặt ở controller, không phải trong model!
