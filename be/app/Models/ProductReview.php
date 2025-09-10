<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductReview extends Model
{
    protected $table = 'product_review';

    protected $primaryKey = 'Product_review_ID';

    public $timestamps = false;

    protected $fillable = [
        'Product_ID',
        'User_ID',
        'Order_ID',
        'Rating',
        'Comment',
        'Image',
        'Status',
        'Created_at',
        'Updated_at',
    ];

    // Mỗi đánh giá thuộc về 1 sản phẩm
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'Product_ID');
    }

    // Mỗi đánh giá thuộc về 1 người dùng
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // Mỗi đánh giá thuộc về 1 đơn hàng
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
}
