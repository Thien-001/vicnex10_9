<?php

// database/seeders/ProductVariantValueSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductVariant;
use App\Models\ProductValue;
use App\Models\ProductVariantValue;

class ProductVariantValueSeeder extends Seeder
{
    public function run(): void
    {
        $variant = ProductVariant::first();
        $colorRed = ProductValue::where('Value', 'Đỏ')->first();
        $sizeM = ProductValue::where('Value', 'M')->first();

        ProductVariantValue::create([
            'Variant_ID' => $variant->Variant_ID,
            'Values_ID' => $colorRed->Values_ID,
        ]);

        ProductVariantValue::create([
            'Variant_ID' => $variant->Variant_ID,
            'Values_ID' => $sizeM->Values_ID,
        ]);
    }
}
