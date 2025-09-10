<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $table = 'categories';
    protected $primaryKey = 'Categories_ID';
    public $timestamps = false;

    protected $fillable = [
        'Name',
        'Slug',
        'Description',
        'Image',
        'Create_at',
        'Update_at',
    ];

    // Auto generate Slug nếu chưa có
    public static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->Slug)) {
                $category->Slug = Str::slug($category->Name);
            }
        });

        static::updating(function ($category) {
            if (empty($category->Slug)) {
                $category->Slug = Str::slug($category->Name);
            }
        });
    }

    // Quan hệ: Một Category có nhiều Product
    public function products(): HasMany
    {
        return $this->hasMany(\App\Models\Product::class, 'Categories_ID', 'Categories_ID');
    }
}
