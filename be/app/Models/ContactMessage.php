<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $table = 'contact_messages'; // Tên bảng

    protected $primaryKey = 'Contact_ID'; // Khóa chính trong bảng

    public $timestamps = false; // Không dùng created_at, updated_at mặc định của Laravel

    protected $fillable = [
        'Name',
        'Email',
        'Phone',
        'Subject',
        'Message',
        'Status',
        'Note',
        'Created_at',
        'Updated_at',
    ];

    protected $casts = [
        'Created_at' => 'datetime',
        'Updated_at' => 'datetime',
    ];
}
