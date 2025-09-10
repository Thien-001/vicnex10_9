<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryAttribute extends Model
{
    protected $table = 'category_attribute';

    protected $fillable = [
        'category_id',
        'attribute_id',
    ];

    // Nếu bảng này không có timestamps
    public $timestamps = false;

    // Nếu cần: quan hệ (optional)
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'Categories_ID');
    }

    public function attribute()
    {
        return $this->belongsTo(ProductAttribute::class, 'attribute_id', 'Attributes_ID');
    }
}
