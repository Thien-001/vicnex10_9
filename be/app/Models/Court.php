<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Court extends Model
{
    use HasFactory;

    protected $table = 'courts';
    protected $primaryKey = 'Courts_ID';

    // Định nghĩa tên cột timestamps nếu khác mặc định
    const CREATED_AT = 'Created_at';
    const UPDATED_AT = 'Updated_at';
    public $timestamps = true;

    // Các trường cho phép gán dữ liệu hàng loạt
    protected $fillable = [
        'Name',
        'location_id',      // Liên kết đến bảng locations
        'Description',
        'Court_type',
        'Price_per_hour',
        'Status',
        'Image',
        'open_time',
        'close_time',
        'Created_at',
        'Updated_at',
    ];

    // Quan hệ: Một sân có nhiều booking
    public function bookings()
    {
        return $this->hasMany(CourtBooking::class, 'Courts_ID', 'Courts_ID');
    }

    // Quan hệ: Một sân thuộc về một địa điểm
    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    // Scope: lọc sân đang hoạt động
    public function scopeActive($query)
    {
        return $query->where('Status', true);
    }

    // Accessor: format giá tiền
    public function getFormattedPriceAttribute()
    {
        return number_format($this->Price_per_hour, 0, ',', '.') . ' VND/giờ';
    }
}
