<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourtBooking extends Model
{
    protected $table = 'court_booking';

    protected $primaryKey = 'Court_booking_ID'; // hoặc 'Booking_ID' đúng tên cột của bạn

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'User_ID',
        'Courts_ID',
        'Booking_date',
        'Start_time',
        'End_time',
        'Duration_hours',
        'Price_per_hour',
        'Total_price',
        'Note',
        'Status',
        'Create_at',
        'Update_at',
        'Vouchers_ID',
    ];

    // Quan hệ: đặt sân thuộc về người dùng
    public function user()
{
    return $this->belongsTo(User::class, 'User_ID', 'ID');
}


    // Quan hệ: đặt sân thuộc về một sân cụ thể
    public function court(): BelongsTo
    {
        return $this->belongsTo(Court::class, 'Courts_ID', 'Courts_ID');
    }

    // Quan hệ: có thể dùng voucher
    public function voucher(): BelongsTo
    {
        return $this->belongsTo(Voucher::class, 'Vouchers_ID', 'Vouchers_ID');
    }


}
