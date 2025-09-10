<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $table = 'vouchers';

    public $timestamps = false;

    protected $fillable = [
        'code',
        'discount_type',
        'discount_value',
        'max_uses',
        'expires',
        'applies_to',
        'paid_at',
    ];

    // Nếu có quan hệ với bảng orders
    // public function orders()
    // {
    //     return $this->hasMany(Order::class, 'vouchers_id', 'Vouchers_ID');
    // }
}
