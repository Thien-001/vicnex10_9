<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostComment extends Model
{
    protected $table = 'post_comments';
    protected $primaryKey = 'Comment_ID';
    protected $fillable = [
        'Post_ID',
        'User_ID',
        'text',
    ];
    public $timestamps = true;
}