<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostComment extends Model
{
    protected $table = 'post_comments';
    protected $primaryKey = 'ID';
    public $timestamps = true;
    protected $fillable = [
        'Post_ID', 'User_ID', 'text'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'User_ID', 'ID');
    }

    public static $rules = [
        'User_ID' => 'required|exists:users,ID',
        'text'    => 'required|string',
    ];
    public function post()
{
    return $this->belongsTo(Post::class, 'Post_ID', 'Post_ID');
}

}
