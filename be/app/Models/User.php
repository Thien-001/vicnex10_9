<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'user'; // Tên bảng là 'user' (khác với mặc định 'users')
    protected $primaryKey = 'ID'; // Đặt đúng tên cột khóa chính

    public $timestamps = false; // Không dùng created_at, updated_at tự động

    public $incrementing = true; // Nếu ID là auto-increment
    protected $keyType = 'int'; // Kiểu dữ liệu của ID là int

    protected $fillable = [
        'Role_ID',
        'Name',
        'Email',
        'Password',
        'Phone',
        'Gender',
        'Date_of_birth',
        'Avatar',
        'Status',
        'Address',
        'ward',      // Thêm dòng này
        'district',  // Thêm dòng này
        'province',  // Thêm dòng này
        'Created_at',
        'Updated_at',
    ];

    protected $hidden = [
        'Password',
        'remember_token',
    ];

    protected $casts = [
        'Date_of_birth' => 'date',
        'Status' => 'boolean',
        'Created_at' => 'datetime',
        'Updated_at' => 'datetime',
        'Password' => 'hashed', // Laravel 10+ mới hỗ trợ tự hash khi dùng fill()
    ];

    // Một user thuộc một vai trò
    public function role()
    {
        return $this->belongsTo(Role::class, 'Role_ID', 'Role_ID');
    }

    // Tùy chọn: Cho phép route model binding hoạt động chính xác
    public function getRouteKeyName()
    {
        return 'ID'; // Dùng 'ID' thay vì mặc định 'id'
    }

}
