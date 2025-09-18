<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $table = 'notifications';

    protected $primaryKey = 'Notifications_ID';

    public $timestamps = true; // Sử dụng timestamps tự động

    protected $fillable = [
        'User_ID',
        'Title',
        'Message',
        'Type',
        'is_read',
        'link',
        'icon',
        'priority'
    ];

    /**
     * Mỗi thông báo thuộc về 1 người dùng.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'User_ID', 'id');
    }
}
