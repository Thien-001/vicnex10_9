<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductAttribute;
use App\Models\ProductValue;

class ProductAttributeSeeder extends Seeder
{
    public function run(): void
    {
        $attributes = [
            ['Name' => 'Màu sắc', 'Description' => 'Màu sản phẩm'],
            ['Name' => 'Kích thước', 'Description' => 'Kích thước sản phẩm'],
            ['Name' => 'Chất liệu', 'Description' => 'Chất liệu của sản phẩm'],
            ['Name' => 'Kiểu dáng', 'Description' => 'Kiểu dáng thiết kế'],
            ['Name' => 'Thương hiệu', 'Description' => 'Thương hiệu sản phẩm'],
            ['Name' => 'Phong cách', 'Description' => 'Phong cách sử dụng'],
            ['Name' => 'Trọng lượng', 'Description' => 'Đơn vị trọng lượng vợt cầu lông (U)'],
        ];

        foreach ($attributes as $attr) {
            $attribute = ProductAttribute::firstOrCreate(['Name' => $attr['Name']], $attr);

            // Nếu là trọng lượng thì thêm các giá trị mặc định
            if ($attr['Name'] === 'Trọng lượng') {
                $weights = ['3U', '4U', '5U'];
                foreach ($weights as $value) {
                    $attribute->values()->firstOrCreate(['Value' => $value]);
                }
            }
        }
    }
}
