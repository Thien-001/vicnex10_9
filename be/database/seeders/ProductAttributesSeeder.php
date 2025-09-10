<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductAttributesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('product_attributes')->insert([
            [
                'Name' => 'Màu sắc',
                'Description' => 'Màu sản phẩm',
                'Create_at' => now(),
            ],
            [
                'Name' => 'Kích thước',
                'Description' => 'Kích thước sản phẩm',
                'Create_at' => now(),
            ],
            [
                'Name' => 'Chất liệu',
                'Description' => 'Chất liệu của sản phẩm',
                'Create_at' => now(),
            ],
            [
                'Name' => 'Kiểu dáng',
                'Description' => 'Kiểu dáng thiết kế',
                'Create_at' => now(),
            ],
            [
                'Name' => 'Thương hiệu',
                'Description' => 'Thương hiệu sản phẩm',
                'Create_at' => now(),
            ],
            [
                'Name' => 'Phong cách',
                'Description' => 'Phong cách sử dụng',
                'Create_at' => now(),
            ],
            [
                'Name' => 'Trọng lượng',
                'Description' => 'Đơn vị trọng lượng vợt cầu lông (U)',
                'Create_at' => now(),
            ],
            [
                'Name' => 'Độ cứng thân vợt',
                'Description' => 'Độ cứng của thân vợt cầu lông',
                'Create_at' => now(),
            ],
            [
                'Name' => 'Điểm cân bằng',
                'Description' => 'Điểm cân bằng của vợt cầu lông',
                'Create_at' => now(),
            ],
            [
                'Name' => 'Lực căng tối đa',
                'Description' => 'Lực căng dây tối đa của vợt',
                'Create_at' => now(),
            ],
            [
                'Name' => 'Kích thước giày',
                'Description' => 'Size giày cầu lông',
                'Create_at' => now(),
            ],
            [
                'Name' => 'Độ bám sàn',
                'Description' => 'Độ bám sàn của giày cầu lông',
                'Create_at' => now(),
            ],
        ]);
    }
}
