<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductAttribute extends Model
{
    protected $table = 'product_attributes';

    protected $primaryKey = 'Attributes_ID';

    public $timestamps = false; // Vì bạn đang dùng Create_at và Update_at riêng

    protected $fillable = [
        'Name',
        'Description',
        'Create_at',
        'Update_at',
    ];

    /**
     * Quan hệ 1-n: Một ProductAttribute có nhiều ProductValue
     */
    public function values(): HasMany
    {
        return $this->hasMany(ProductValue::class, 'Attributes_ID', 'Attributes_ID');
    }
}
