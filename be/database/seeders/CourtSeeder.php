<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CourtSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('courts')->insert([
            [
                'Name' => 'Sân Cầu Lông A1',
                'Location' => 'Vinhomes Grand Park',
                'Description' => 'Sân chất lượng cao, có mái che, hệ thống đèn LED hiện đại.',
                'Image' => 'courts/a1.jpg',
                'Court_type' => 'Trong nhà',
                'Price_per_hour' => 150000,
                'Status' => true,
                'Created_at' => Carbon::now(),
                'Updated_at' => Carbon::now(),
            ],
            [
                'Name' => 'Sân Cầu Lông B2',
                'Location' => 'Saigon Pearl',
                'Description' => 'Sân ngoài trời, phù hợp chơi buổi sáng hoặc chiều mát.',
                'Image' => 'courts/b2.jpg',
                'Court_type' => 'Ngoài trời',
                'Price_per_hour' => 100000,
                'Status' => true,
                'Created_at' => Carbon::now(),
                'Updated_at' => Carbon::now(),
            ],
            [
                'Name' => 'Sân Cầu Lông C3',
                'Location' => 'Vinhomes Central Park',
                'Description' => 'Sân mới nâng cấp nền cao su chống trượt.',
                'Image' => 'courts/c3.jpg',
                'Court_type' => 'Trong nhà',
                'Price_per_hour' => 180000,
                'Status' => false,
                'Created_at' => Carbon::now(),
                'Updated_at' => Carbon::now(),
            ],
        ]);
    }
}
