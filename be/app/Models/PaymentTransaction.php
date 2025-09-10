<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentTransaction extends Model
{
    protected $table = 'payment_transactions';

    protected $primaryKey = 'Payment_ID';

    public $timestamps = false;

    protected $fillable = [
        'User_ID',
        'Order_ID',
        'Court_booking_ID',
        'Amount',
        'Method',
        'Status',
        'Transaction_code',
        'Paid_at',
    ];

    // Quan hệ: Giao dịch thuộc về người dùng
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'User_ID', 'ID');
    }

    // Quan hệ: Giao dịch có thể thuộc về đơn hàng
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'Order_ID', 'order_id');
    }

    // Quan hệ: Giao dịch có thể thuộc về đặt sân (nếu bảng tồn tại)
    public function courtBooking(): BelongsTo
    {
        return $this->belongsTo(CourtBooking::class, 'Court_booking_ID', 'Court_booking_ID');
    }
}
