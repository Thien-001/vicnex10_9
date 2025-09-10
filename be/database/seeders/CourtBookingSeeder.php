<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CourtBookingSeeder extends Seeder
{
    public function run(): void
    {
        // Lấy ID từ các bảng liên quan
        $userIds = DB::table('user')->pluck('ID')->toArray();
        $courtIds = DB::table('courts')->pluck('Courts_ID')->toArray();
        $voucherIds = DB::table('vouchers')->pluck('Vouchers_ID')->toArray();

        // Nếu thiếu dữ liệu liên quan, không seed
        if (empty($userIds) || empty($courtIds)) {
            echo "⚠️ Không có dữ liệu user hoặc courts để seed court_booking.\n";
            return;
        }

        $data = [];

        for ($i = 0; $i < 10; $i++) {
            $bookingDate = Carbon::now()->addDays(rand(1, 15))->format('Y-m-d');
            $startHour = rand(7, 18); // 7h đến 18h
            $duration = rand(1, 3); // 1–3 giờ
            $endHour = $startHour + $duration;

            $price = 100000 + rand(0, 2) * 20000; // 100k–140k
            $total = $price * $duration;

            $data[] = [
                'User_ID'       => $userIds[array_rand($userIds)],
                'Courts_ID'     => $courtIds[array_rand($courtIds)],
                'Booking_date'  => $bookingDate,
                'Start_time'    => sprintf('%02d:00:00', $startHour),
                'End_time'      => sprintf('%02d:00:00', $endHour),
                'Duration_hours'=> $duration,
                'Price_per_hour'=> $price,
                'Total_price'   => $total,
                'Note'          => rand(0, 1) ? 'Mang theo vợt riêng' : null,
                'Status'        => true,
                'Create_at'     => now(),
                'Update_at'     => null,
                'Vouchers_ID'   => $voucherIds ? $voucherIds[array_rand($voucherIds)] : null,
            ];
        }

        DB::table('court_booking')->insert($data);
    }
}
