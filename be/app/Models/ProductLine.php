<?php
// filepath: /home/titus/Documents/DATN/vicnex10_9/be/app/Models/ProductLine.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductLine extends Model
{
    use HasFactory;

    protected $table = 'product_lines';
    
    protected $fillable = [
        'name',
        'slug', 
        'brand',
        'description',
        'image',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationship với Products
    public function products()
    {
        return $this->hasMany(Product::class, 'product_line_id', 'id');
    }

    // Scope active lines
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope by brand
    public function scopeByBrand($query, $brand)
    {
        return $query->where('brand', $brand);
    }
}