<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariantValue extends Model
{
    protected $table = 'product_variant_values';
    protected $primaryKey = 'id'; // Hoặc 'ProductVariantValue_ID' nếu bạn đặt tên khác
    public $timestamps = false;

    protected $fillable = ['Variant_ID', 'Values_ID'];

    /**
     * Mỗi giá trị biến thể thuộc về một biến thể sản phẩm
     */
    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'Variant_ID', 'Variant_ID');
    }

    /**
     * Mỗi giá trị biến thể tương ứng với một giá trị (product value)
     */
    public function productValue(): BelongsTo
    {
        return $this->belongsTo(ProductValue::class, 'Values_ID', 'Values_ID');
    }

    /**
     * Lấy luôn attribute thông qua product value
     * Để dùng: $variantValue->productValue->attribute
     */
}
