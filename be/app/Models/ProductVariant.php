<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductVariant extends Model
{
    protected $table = 'product_variants';
    protected $primaryKey = 'Variant_ID';
    public $timestamps = false; // Vì bạn dùng Created_at / Update_at tùy chỉnh

    protected $fillable = [
        'Product_ID',
        'SKU',
        'Variant_name',
        'Price',
        'Discount_price',
        'Quantity',
        'Image',
        'Status',
        'Created_at',
        'Update_at',
    ];

    protected $casts = [
        'Price' => 'decimal:2',
        'Discount_price' => 'decimal:2',
        'Quantity' => 'integer',
        'Status' => 'integer',
        'Created_at' => 'datetime',
        'Update_at' => 'datetime'
    ];

    /**
     * SỬA: Bỏ return type vì Laravel cũ có thể không support
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'Product_ID', 'Product_ID');
    }

    public function values()
    {
        return $this->belongsToMany(
            ProductValue::class,
            'product_variant_values',
            'Variant_ID',
            'Values_ID'
        );
    }

    public function scopeActive($query)
    {
        return $query->where('Status', 1);
    }

    public function scopeInStock($query)
    {
        return $query->where('Quantity', '>', 0);
    }
}
