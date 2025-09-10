<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CategoriesTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categories')->insert([
            [
                'Categories_ID' => 1,
                'Name' => 'Vợt cầu lông',
                'Description' => 'Đa dạng vợt cho người mới đến vận động viên chuyên nghiệp.',
                'Image' => 'img/categories/vot-cau-long.jpg',
                'Create_at' => Carbon::now(),
                'Update_at' => Carbon::now(),
            ],
            [
                'Categories_ID' => 2,
                'Name' => 'Giày cầu lông',
                'Description' => 'Giày chuyên dụng hỗ trợ di chuyển và giảm chấn hiệu quả.',
                'Image' => 'img/categories/giay-cau-long.jpg',
                'Create_at' => Carbon::now(),
                'Update_at' => Carbon::now(),
            ],
            [
                'Categories_ID' => 3,
                'Name' => 'Quần áo cầu lông',
                'Description' => 'Thời trang thể thao thoải mái, co giãn tốt, hút ẩm nhanh.',
                'Image' => 'img/categories/quan-ao-cau-long.jpg',
                'Create_at' => Carbon::now(),
                'Update_at' => Carbon::now(),
            ],
            [
                'Categories_ID' => 4,
                'Name' => 'Phụ kiện cầu lông',
                'Description' => 'Bao vợt, quấn cán, dây vợt, băng gối, khăn thể thao,...',
                'Image' => 'img/categories/phu-kien-cau-long.jpg',
                'Create_at' => Carbon::now(),
                'Update_at' => Carbon::now(),
            ],
            [
                'Categories_ID' => 5,
                'Name' => 'Combo tiết kiệm',
                'Description' => 'Các gói sản phẩm ưu đãi, tiết kiệm hơn khi mua theo bộ.',
                'Image' => 'img/categories/combo-tiet-kiem.jpg',
                'Create_at' => Carbon::now(),
                'Update_at' => Carbon::now(),
            ],
            [
                'Categories_ID' => 6,
                'Name' => 'Hàng giảm giá',
                'Description' => 'Sản phẩm đang trong chương trình khuyến mãi, ưu đãi.',
                'Image' => 'img/categories/hang-giam-gia.jpg',
                'Create_at' => Carbon::now(),
                'Update_at' => Carbon::now(),
            ],
            [
                'Categories_ID' => 7,
                'Name' => 'Mới về',
                'Description' => 'Sản phẩm mới nhất vừa cập bến cửa hàng.',
                'Image' => 'img/categories/moi-ve.jpg',
                'Create_at' => Carbon::now(),
                'Update_at' => Carbon::now(),
            ],
            [
                'Categories_ID' => 8,
                'Name' => 'Top bán chạy',
                'Description' => 'Những sản phẩm được nhiều khách hàng tin dùng nhất.',
                'Image' => 'img/categories/top-ban-chay.jpg',
                'Create_at' => Carbon::now(),
                'Update_at' => Carbon::now(),
            ],
        ]);
    }
}
