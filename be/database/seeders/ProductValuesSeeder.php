<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductValuesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Màu sắc (Attributes_ID = 1)
        DB::table('product_values')->insert([
            ['Attributes_ID' => 1, 'value' => 'Đỏ'],
            ['Attributes_ID' => 1, 'value' => 'Xanh'],
            ['Attributes_ID' => 1, 'value' => 'Vàng'],
            ['Attributes_ID' => 1, 'value' => 'Đen'],
            ['Attributes_ID' => 1, 'value' => 'Trắng'],
        ]);

        // Kích thước (Attributes_ID = 2)
        DB::table('product_values')->insert([
            ['Attributes_ID' => 2, 'value' => 'S'],
            ['Attributes_ID' => 2, 'value' => 'M'],
            ['Attributes_ID' => 2, 'value' => 'L'],
            ['Attributes_ID' => 2, 'value' => 'XL'],
        ]);

        // Chất liệu (Attributes_ID = 3)
        DB::table('product_values')->insert([
            ['Attributes_ID' => 3, 'value' => 'Carbon'],
            ['Attributes_ID' => 3, 'value' => 'Graphite'],
            ['Attributes_ID' => 3, 'value' => 'Nhôm'],
        ]);

        // Kiểu dáng (Attributes_ID = 4)
        DB::table('product_values')->insert([
            ['Attributes_ID' => 4, 'value' => 'Công thủ'],
            ['Attributes_ID' => 4, 'value' => 'Tấn công'],
            ['Attributes_ID' => 4, 'value' => 'Phòng thủ'],
        ]);

        // Phong cách (Attributes_ID = 6)
        DB::table('product_values')->insert([
            ['Attributes_ID' => 6, 'value' => 'Chuyên nghiệp'],
            ['Attributes_ID' => 6, 'value' => 'Phong trào'],
        ]);

        // Trọng lượng (Attributes_ID = 7)
        DB::table('product_values')->insert([
            ['Attributes_ID' => 7, 'value' => '3U'],
            ['Attributes_ID' => 7, 'value' => '4U'],
            ['Attributes_ID' => 7, 'value' => '5U'],
        ]);

        // Độ cứng thân vợt (Attributes_ID = 15)
        DB::table('product_values')->insert([
            ['Attributes_ID' => 15, 'value' => 'Dẻo'],
            ['Attributes_ID' => 15, 'value' => 'Trung bình'],
            ['Attributes_ID' => 15, 'value' => 'Cứng'],
        ]);

        // Điểm cân bằng (Attributes_ID = 16)
        DB::table('product_values')->insert([
            ['Attributes_ID' => 16, 'value' => 'Head Heavy'],
            ['Attributes_ID' => 16, 'value' => 'Even Balance'],
            ['Attributes_ID' => 16, 'value' => 'Head Light'],
        ]);

        // Lực căng tối đa (Attributes_ID = 17)
        DB::table('product_values')->insert([
            ['Attributes_ID' => 17, 'value' => '28 lbs'],
            ['Attributes_ID' => 17, 'value' => '30 lbs'],
            ['Attributes_ID' => 17, 'value' => '32 lbs'],
        ]);

        // Kích thước giày (Attributes_ID = 18)
        DB::table('product_values')->insert([
            ['Attributes_ID' => 18, 'value' => '39'],
            ['Attributes_ID' => 18, 'value' => '40'],
            ['Attributes_ID' => 18, 'value' => '41'],
            ['Attributes_ID' => 18, 'value' => '42'],
            ['Attributes_ID' => 18, 'value' => '43'],
        ]);

        // Độ bám sàn (Attributes_ID = 19)
        DB::table('product_values')->insert([
            ['Attributes_ID' => 19, 'value' => 'Cao'],
            ['Attributes_ID' => 19, 'value' => 'Trung bình'],
            ['Attributes_ID' => 19, 'value' => 'Thấp'],
        ]);
    }
}
