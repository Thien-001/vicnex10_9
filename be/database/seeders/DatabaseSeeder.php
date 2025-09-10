<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Seed dữ liệu phụ thuộc trước (Roles, Categories, Courts)
        $this->call([
            RolesTableSeeder::class,
            CategoriesTableSeeder::class,
            CourtSeeder::class,
        ]);

        // Seed dữ liệu chính (Attributes, Products, Values, Variants)
        $this->call([
            ProductAttributeSeeder::class,
            ProductsTableSeeder::class,
            ProductValueSeeder::class,
            ProductVariantSeeder::class,
            ProductVariantValueSeeder::class,
        ]);


        // Seed Orders và các chi tiết liên quan
        $this->call([
            OrderSeeder::class,
            // OrderDetailSeeder::class, // Nếu có
        ]);
    }
}
