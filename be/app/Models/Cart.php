<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cart extends Model
{
    protected $table = 'carts';

    protected $primaryKey = 'Cart_ID';

    protected $fillable = [
        'User_ID',
        'Product_ID',
        'Quantity',
        'Price',
    ];

    // Quan hệ: Cart thuộc về User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'User_ID', 'ID');
    }

    // Quan hệ: Cart thuộc về Product
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'Product_ID', 'Product_ID');
    }
}
