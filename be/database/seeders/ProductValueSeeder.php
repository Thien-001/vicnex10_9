<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductValue;
use App\Models\ProductAttribute;

class ProductValueSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Màu sắc' => ['Đỏ', 'Xanh', 'Đen', 'Trắng', 'Vàng'],
            'Kích thước' => ['S', 'M', 'L', 'XL', 'XXL'],
            'Chất liệu' => ['Cotton', 'Polyester', 'Da', 'Len', 'Sợi tổng hợp'],
            'Kiểu dáng' => ['Thể thao', 'Công sở', 'Cá tính', 'Đơn giản'],
            'Thương hiệu' => ['Nike', 'Adidas', 'Puma', 'Local Brand'],
            'Phong cách' => ['Hiện đại', 'Cổ điển', 'Vintage'],
        ];

        foreach ($data as $attributeName => $values) {
            $attribute = ProductAttribute::where('Name', $attributeName)->first();

            if ($attribute) {
                foreach ($values as $value) {
                    ProductValue::create([
                        'Attributes_ID' => $attribute->Attributes_ID,
                        'Value' => $value,
                    ]);
                }
            } else {
                $this->command->warn("⚠️ Không tìm thấy thuộc tính: $attributeName");
            }
        }
    }
}
