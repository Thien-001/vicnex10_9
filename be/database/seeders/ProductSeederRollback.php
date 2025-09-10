<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeederRollback extends Seeder
{
    public function run(): void
    {
        // Xoá dữ liệu theo thứ tự phụ thuộc
        DB::table('product_variant_values')->delete();   // phụ thuộc product_values & product_variants
        DB::table('product_values')->delete();           // phụ thuộc product_attributes
        DB::table('product_attributes')->delete();

        DB::table('product_variants')->delete();         // phụ thuộc products
        DB::table('favorites')->delete();                // phụ thuộc products
        DB::table('carts')->delete();                    // nếu có ràng buộc products
        DB::table('order_details')->delete();            // nếu có ràng buộc products

        DB::table('products')->delete();                 // cuối cùng là bảng chính

        // Nếu bạn muốn reset auto increment ID (tùy chọn)
        DB::statement('ALTER TABLE products AUTO_INCREMENT = 1');
    }
}
