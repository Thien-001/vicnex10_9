<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $table = 'product_images';
    protected $primaryKey = 'Image_ID'; // nếu bạn có cột ID riêng
    public $timestamps = false;

    protected $fillable = [
        'Product_ID',
        'Image_path',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'Product_ID', 'Product_ID');
    }
}
