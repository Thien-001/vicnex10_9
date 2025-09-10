<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\User;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        // Tạo user mẫu nếu chưa có
        if (User::count() < 5) {
            for ($i = 1; $i <= 5; $i++) {
                User::firstOrCreate([
                    'Email' => "user$i@example.com"
                ], [
                    'Name' => "User $i",
                    'Password' => bcrypt('password'), // hoặc Hash::make()
                    'Phone' => '012345678' . $i,
                    'Gender' => 'male',
                    'Date_of_birth' => '2000-01-01',
                    'Address' => 'TP.HCM',
                    'Status' => 1,
                    'Role_ID' => 3 // đảm bảo Role_ID 3 là 'user'
                ]);
            }
        }

        $userIds = User::pluck('ID')->toArray(); // Lấy danh sách ID người dùng

        $addresses = ['Hà Nội', 'TP.HCM', 'Đà Nẵng', 'Cần Thơ', 'Hải Phòng'];
        $methods   = ['COD', 'Bank Transfer', 'Momo', 'ZaloPay'];
        $statuses  = ['pending', 'shipping', 'completed', 'cancelled'];

        for ($i = 0; $i < 10; $i++) {
            Order::create([
                'user_id'          => $userIds[array_rand($userIds)],
                'order_code'       => 'ORD-' . strtoupper(Str::random(6)),
                'shipping_address' => $addresses[array_rand($addresses)],
                'note_user'        => rand(0, 1) ? 'Giao vào buổi sáng' : null,
                'payment_method'   => $methods[array_rand($methods)],
                'shiping_fee'      => rand(10000, 50000),
                'total_amount'     => rand(100000, 2000000),
                'status'           => $statuses[array_rand($statuses)],
                'status_method'    => rand(0, 1) ? 'Chờ xác nhận' : 'Đang xử lý',
                'vourchers_id'     => null,
                'created_at'       => now()->subDays(rand(1, 30)),
                'updated_at'       => now(),
            ]);
        }
    }
}
