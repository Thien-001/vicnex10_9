<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'id';

    protected $fillable = [
        'full_name',
        'phone',
        'email',
        'province_code',
        'district_code',
        'ward_code',
        'address',
        'note',
        'status',
        'payment_method',
        'total_price',
        'shipping_fee',
        'voucher_id',
        'user_id',
    ];

    // Một đơn hàng thuộc về một người dùng (nếu có user_id)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Chi tiết đơn hàng
    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class, 'order_id', 'id');
    }

    // Quan hệ với tỉnh/thành phố
    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class, 'province_code', 'code');
    }

    // Quan hệ với quận/huyện
    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class, 'district_code', 'code');
    }

    // Quan hệ với phường/xã
    public function ward(): BelongsTo
    {
        return $this->belongsTo(Ward::class, 'ward_code', 'code');
    }

    // Quan hệ với voucher
    public function voucher(): BelongsTo
    {
        return $this->belongsTo(Voucher::class, 'voucher_id', 'id');
    }

    // Quan hệ chi tiết đơn hàng cho admin (tên details)
    public function details(): HasMany
    {
        return $this->hasMany(OrderDetail::class, 'order_id', 'id');
    }
}
