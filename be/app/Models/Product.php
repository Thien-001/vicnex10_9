<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    // Tên bảng trong CSDL
    protected $table = 'products';

    // Khóa chính
    protected $primaryKey = 'Product_ID';

    // Tắt timestamps mặc định (created_at, updated_at)
    public $timestamps = false;

    // Các cột có thể gán dữ liệu hàng loạt
    protected $fillable = [
        'Name',
        'SKU',
        'Brand',
        'Categories_ID',
        'product_line_id', // THÊM DÒNG NÀY
        'Image',
        'Description',
        'Price',
        'Discount_price',
        'Quantity',
        'Status',
        'Created_at',
        'Updated_at',
        'is_featured',
        'is_hot',
        'is_best_seller',
        'rating',
        'details',
        'expert_review',
        'slug',
    ];

    // Kiểu dữ liệu cho các cột
    protected $casts = [
        'Created_at'      => 'datetime',
        'Updated_at'      => 'datetime',
        'Price'           => 'decimal:2',
        'Discount_price'  => 'decimal:2',
        'Status'          => 'boolean',
        'is_featured'     => 'boolean',
        'is_hot'          => 'boolean',
        'is_best_seller'  => 'boolean',
        'rating'          => 'float',
        'details'         => 'string',
    ];

    // Quan hệ: Sản phẩm thuộc về danh mục
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'Categories_ID', 'Categories_ID');
    }

    // Quan hệ: Sản phẩm có nhiều biến thể
    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class, 'Product_ID', 'Product_ID');
    }

    // Quan hệ: Sản phẩm có một biến thể chính
    public function variant(): HasOne
    {
        return $this->hasOne(ProductVariant::class, 'Product_ID', 'Product_ID');
    }

    // Quan hệ: Sản phẩm có nhiều giá trị thuộc tính qua bảng trung gian
    public function attributeValues(): BelongsToMany
    {
        return $this->belongsToMany(
            AttributeValue::class,
            'product_variant_values',
            'Product_ID',
            'Values_ID',
            'Product_ID',
            'Values_ID'
        )->with('attribute');
    }

    // Quan hệ: Sản phẩm có nhiều hình ảnh
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class, 'Product_ID', 'Product_ID');
    }

    // Quan hệ: Sản phẩm có nhiều chi tiết đơn hàng
    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class, 'Product_ID', 'Product_ID');
    }

    // Quan hệ: Sản phẩm có nhiều đánh giá chuyên gia
    public function expertReviews(): HasMany
    {
        return $this->hasMany(ExpertReview::class, 'product_id', 'Product_ID');
    }

    // Quan hệ: Sản phẩm thuộc về thương hiệu
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'id');
    }

    // Quan hệ: Sản phẩm thuộc về dòng sản phẩm
    public function productLine(): BelongsTo
    {
        return $this->belongsTo(ProductLine::class, 'product_line_id', 'id');
    }

    // Quan hệ: Sản phẩm có nhiều đánh giá từ users
    public function ratings(): HasMany
    {
        return $this->hasMany(ProductRating::class, 'Product_ID', 'Product_ID');
    }

    // Override để tìm sản phẩm theo slug thay vì ID
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // Tự động tạo slug khi tạo hoặc cập nhật
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = static::createUniqueSlug($product->Name);
            }
        });

        static::updating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = static::createUniqueSlug($product->Name, $product->Product_ID);
            }
        });
    }

    // Hàm tạo slug duy nhất, thêm số nếu trùng
    protected static function createUniqueSlug($name, $ignoreId = null)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $count = 1;

        while (static::where('slug', $slug)
            ->when($ignoreId, fn($query) => $query->where('Product_ID', '!=', $ignoreId))
            ->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        return $slug;
    }

    // Tổng số lượng: nếu có biến thể thì cộng quantity các biến thể, không thì lấy quantity sản phẩm gốc
    public function getTotalQuantityAttribute()
    {
        if ($this->variants && $this->variants->count() > 0) {
            return $this->variants->sum('Quantity');
        }
        return $this->Quantity;
    }
}
