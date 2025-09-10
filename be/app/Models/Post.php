<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    protected $table = 'posts';

    protected $primaryKey = 'Post_ID';

    public $timestamps = true; // Đổi thành true nếu dùng timestamps của Laravel

    protected $fillable = [
        'User_ID', 'Category_ID', 'Title', 'Thumbnail', 'Content', 'Excerpt',
        'Status', 'View', 'Meta_Title', 'Meta_Description', 'Is_Featured', 'Slug'
    ];

    /**
     * Bài viết thuộc về một người dùng
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'User_ID', 'ID'); // Sửa 'id' thành 'ID'
    }

    /**
     * Bài viết thuộc về một danh mục
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(PostCategory::class, 'Category_ID', 'id');
    }

    /**
     * Bài viết có thể có nhiều bình luận
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'Post_ID', 'Post_ID');
    }
}
