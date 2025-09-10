<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductRating extends Model
{
    protected $table = 'product_ratings';

    protected $fillable = [
        'Product_ID',
        'User_ID',
        'Rating',
        'image', // thêm dòng này để cho phép lưu ảnh
        'text', // <-- Thêm dòng này!
    ];

    // Nếu không dùng timestamps thì bỏ comment dòng dưới
    // public $timestamps = false;

    // Thêm quan hệ tới user (bảng user, cột ID)
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'User_ID', 'ID');
    }
}
