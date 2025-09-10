<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductVariant;

class ProductVariantSeeder extends Seeder
{
    public function run(): void
    {
        ProductVariant::create([
            'Product_ID' => Product::first()->Product_ID,
            'SKU' => 'YONEX-123-R',
            'Variant_name' => 'Đỏ - M',
            'Price' => 1000000,
            'Quantity' => 20,
            'Image' => 'storage/uploads/products/variant-red.jpg',
            // 'created_at' => now(),
            // 'updated_at' => now(),
        ]);
    }
}
