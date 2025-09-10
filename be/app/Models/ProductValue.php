<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductValue extends Model
{
    protected $table = 'product_values';

    protected $primaryKey = 'Values_ID';

    public $timestamps = false;

    protected $fillable = [
        'Attributes_ID',
        'Value',
        'Create_at',
        'Update_at',
    ];

    /**
     * Mỗi giá trị thuộc về một thuộc tính
     */
    public function attribute(): BelongsTo
{
    return $this->belongsTo(ProductAttribute::class, 'Attributes_ID', 'Attributes_ID');
}


    /**
     * Một giá trị có thể được sử dụng trong nhiều biến thể
     */
    public function variantValues(): HasMany
    {
        return $this->hasMany(ProductVariantValue::class, 'Values_ID', 'Values_ID');
    }

    /**
     * The variants that belong to the product value.
     */
    public function variants()
    {
        return $this->belongsToMany(
            ProductVariant::class,
            'product_variant_values',
            'Values_ID',
            'Variant_ID'
        );
    }
}
