<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Expert extends Model
{
    protected $table = 'experts';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'name',
        'photo',
        'position',
        'bio',
    ];

    // Nếu có bảng expert_reviews liên kết qua expert_id
    public function reviews(): HasMany
    {
        return $this->hasMany(\App\Models\ExpertReview::class, 'expert_id', 'id');
    }
}
