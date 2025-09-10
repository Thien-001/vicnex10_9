<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cart;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class CartSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        // Lấy danh sách ID có sẵn từ user và product
        $userIds = DB::table('user')->pluck('ID')->toArray();
        $productIds = DB::table('products')->pluck('Product_ID')->toArray();

        // Tạo 10 dòng dữ liệu giỏ hàng
        for ($i = 0; $i < 10; $i++) {
            Cart::create([
                'User_ID'    => $faker->randomElement($userIds),
                'Product_ID' => $faker->randomElement($productIds),
                'Quantity'   => $faker->numberBetween(1, 5),
                'Price'      => $faker->randomFloat(2, 100000, 1000000),
            ]);
        }
    }
}
