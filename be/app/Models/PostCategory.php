<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostCategory extends Model
{
    protected $table = 'post_categories';

    protected $fillable = [
        'Name',
        'Slug',
        'Description',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class, 'Category_ID', 'id');
    }
}
