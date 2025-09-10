<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpertReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'expert_id',
        'content',
    ];

    public function expert()
    {
        return $this->belongsTo(\App\Models\Expert::class, 'expert_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(\App\Models\Product::class, 'product_id');
    }
}
