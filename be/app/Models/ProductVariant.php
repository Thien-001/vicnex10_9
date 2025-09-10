<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductVariant extends Model
{
    protected $table = 'product_variants';
    protected $primaryKey = 'Variant_ID';
    public $timestamps = false; // vì bạn đang dùng Created_at / Update_at tùy chỉnh

    protected $fillable = [
        'Product_ID',
        'SKU',
        'Variant_name',
        'Price',
        'Discount_price',
        'Quantity',
        'Status',
        'Created_at',
        'Update_at', // 🛠 sửa lại cho đúng tên cột trong migration
    ];

    /**
     * Sản phẩm mà biến thể này thuộc về
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'Product_ID');
    }

    /**
     * Các giá trị thuộc tính gắn với biến thể
     */
    public function values(): BelongsToMany
    {
        return $this->belongsToMany(
            ProductValue::class,
            'product_variant_values', // ✅ sửa lại đúng tên bảng bạn đã tạo
            'Variant_ID',
            'Values_ID'
        );
    }
}
