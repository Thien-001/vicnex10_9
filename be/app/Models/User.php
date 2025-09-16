<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $table = 'user'; // Đúng tên bảng
    protected $primaryKey = 'ID'; // Đúng tên cột khóa chính

    public $timestamps = false; // Nếu không dùng created_at, updated_at tự động
    public $incrementing = true;
    protected $keyType = 'int';

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
        'ward',
        'district',
        'province',
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
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'Role_ID', 'Role_ID');
    }

    public function getRouteKeyName()
    {
        return 'ID';
    }

    // JWT methods
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
