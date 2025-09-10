<?php
// app/Models/CommentRating.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommentRating extends Model
{
    protected $table = 'comment_ratings';
    protected $fillable = ['Comment_ID', 'User_ID', 'Type'];
    public $timestamps = false;
}