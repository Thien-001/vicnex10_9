<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlashSale extends Model
{
    protected $table = 'flash_sales';

    protected $fillable = [
        'product_id',
        'price_sale',
        'price_old',
        'discount',
        'start_time',
        'end_time',
        'status',
        'is_show', // thêm trường này để cập nhật trạng thái hiển thị
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'Product_ID');
    }
}
