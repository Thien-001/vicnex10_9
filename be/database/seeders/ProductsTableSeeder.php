<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->delete();


        $products = [
            [
    'Categories_ID' => 1,
    'Name' => 'Yonex Astrox 100ZZ',
    'SKU' => 'AX100ZZ',
    'Brand' => 'Yonex',
    'SLUG' => 'yonex-astrox-100zz',
    'Description' => 'Flagship Astrox cán extra stiff, head-heavy, công nghệ Namd, Nanometric – vợt tấn công cao cấp.',
    'Image' => 'img/products/yonex-astrox-100zz.jpg',
    'Price' => 295000,
    'Discount_price' => 279000,
    'Quantity' => 15,
    'Status' => true,
    'Created_at' => now(),
    'updated_at' => now(), // ✅ Đúng

],
[
    'Categories_ID' => 1,
    'Name' => 'Yonex Astrox 99 Pro',
    'SKU' => 'AX99P',
    'Brand' => 'Yonex',
    'SLUG' => 'yonex-astrox-99-pro',
    'Description' => 'Astrox 99 Pro – vợt tấn công toàn diện, head-heavy, cán stiff. Công nghệ mới nâng cấp từ bản 99.',
    'Image' => 'img/products/yonex-astrox-99-pro.jpg',
    'Price' => 179900,
    'Discount_price' => 169900,
    'Quantity' => 20,
    'Status' => true,
    'Created_at' => now(),
    'updated_at' => now(), // ✅ Đúng

],
[
    'Categories_ID' => 1,
    'Name' => 'Yonex Nanoflare 800',
    'SKU' => 'NF800',
    'Brand' => 'Yonex',
    'SLUG' => 'yonex-nanoflare-800',
    'Description' => 'Nanoflare 800 – vợt nhẹ, tốc độ phản tạt nhanh, shaft siêu mỏng, head-light. Phù hợp đánh đôi.',
    'Image' => 'img/products/yonex-nanoflare-800.jpg',
    'Price' => 149900,
    'Discount_price' => 139900,
    'Quantity' => 25,
    'Status' => true,
    'Created_at' => now(),
    'updated_at' => now(), // ✅ Đúng

],
[
    'Categories_ID' => 1,
    'Name' => 'Yonex Arcsaber 11 Pro',
    'SKU' => 'ARC11P',
    'Brand' => 'Yonex',
    'SLUG' => 'yonex-arcsaber-11-pro',
    'Description' => 'Vợt kiểm soát – Arcsaber 11 Pro có thân mềm trung bình, chính xác cao, phù hợp đánh đơn đôi toàn năng.',
    'Image' => 'img/products/yonex-arcsaber-11-pro.jpg',
    'Price' => 169000,
    'Discount_price' => 155000,
    'Quantity' => 18,
    'Status' => true,
    'Created_at' => now(),
    'updated_at' => now(), // ✅ Đúng

],
[
    'Categories_ID' => 1,
    'Name' => 'Yonex Astrox 88D Pro',
    'SKU' => 'AX88DP',
    'Brand' => 'Yonex',
    'SLUG' => 'yonex-astrox-88d-pro',
    'Description' => 'Astrox 88D Pro – chuyên đánh đôi vị trí sau sân, hỗ trợ smash mạnh, power shaft.',
    'Image' => 'img/products/yonex-astrox-88d-pro.jpg',
    'Price' => 165000,
    'Discount_price' => 152000,
    'Quantity' => 30,
    'Status' => true,
    'Created_at' => now(),
    'updated_at' => now(), // ✅ Đúng

],
[
    'Categories_ID' => 1,
    'Name' => 'Yonex Astrox 88S Pro',
    'SKU' => 'AX88SP',
    'Brand' => 'Yonex',
    'SLUG' => 'yonex-astrox-88s-pro',
    'Description' => 'Astrox 88S Pro – dành cho người chơi đôi điều cầu, tốc độ nhanh, kiểm soát chính xác.',
    'Image' => 'img/products/yonex-astrox-88s-pro.jpg',
    'Price' => 165000,
    'Discount_price' => 152000,
    'Quantity' => 26,
    'Status' => true,
    'Created_at' => now(),
    'updated_at' => now(), // ✅ Đúng

],
[
    'Categories_ID' => 1,
    'Name' => 'Yonex Duora Z-Strike',
    'SKU' => 'DZSTRIKE',
    'Brand' => 'Yonex',
    'SLUG' => 'yonex-duora-z-strike',
    'Description' => 'Duora Z-Strike – khung vợt 2 mặt: công và thủ, thiết kế độc quyền cho tấn công lẫn phòng thủ.',
    'Image' => 'img/products/yonex-duora-z-strike.jpg',
    'Price' => 160000,
    'Discount_price' => 149000,
    'Quantity' => 20,
    'Status' => true,
    'Created_at' => now(),
    'updated_at' => now(), // ✅ Đúng

],
[
    'Categories_ID' => 1,
    'Name' => 'Yonex Nanoflare 700',
    'SKU' => 'NF700',
    'Brand' => 'Yonex',
    'SLUG' => 'yonex-nanoflare-700',
    'Description' => 'Nanoflare 700 – nhẹ, linh hoạt, tốc độ xử lý cao, dễ điều khiển. Phù hợp nữ hoặc người mới.',
    'Image' => 'img/products/yonex-nanoflare-700.jpg',
    'Price' => 139000,
    'Discount_price' => 129000,
    'Quantity' => 22,
    'Status' => true,
    'Created_at' => now(),
    'updated_at' => now(), // ✅ Đúng

],
[
    'Categories_ID' => 1,
    'Name' => 'Yonex Carbonex 21',
    'SKU' => 'CAB21',
    'Brand' => 'Yonex',
    'SLUG' => 'yonex-carbonex-21',
    'Description' => 'Carbonex 21 – dòng vợt cổ điển Yonex bền bỉ, cứng cáp, dùng được cả phong trào lẫn chuyên nghiệp.',
    'Image' => 'img/products/yonex-carbonex-21.jpg',
    'Price' => 99000,
    'Discount_price' => 89000,
    'Quantity' => 40,
    'Status' => true,
    'Created_at' => now(),
    'updated_at' => now(), // ✅ Đúng

],
[
    'Categories_ID' => 1,
    'Name' => 'Yonex Muscle Power 29 Light',
    'SKU' => 'MP29L',
    'Brand' => 'Yonex',
    'SLUG' => 'yonex-muscle-power-29-light',
    'Description' => 'Vợt phổ thông dễ chơi, nhẹ tay, phù hợp người mới bắt đầu hoặc học sinh sinh viên.',
    'Image' => 'img/products/yonex-muscle-power-29-light.jpg',
    'Price' => 89000,
    'Discount_price' => 82000,
    'Quantity' => 50,
    'Status' => true,
    'Created_at' => now(),
    'updated_at' => now(), // ✅ Đúng

],[
    'Categories_ID' => 1,
    'Name' => 'Li‑Ning Windstorm 72',
    'SKU' => 'LINING-WS72',
    'Brand' => 'Lining',
    'SLUG' => 'lining-windstorm-72',
    'Description' => 'Windstorm 72 – siêu nhẹ (~70 g), tốc độ phản tạt nhanh, phù hợp chơi đôi chuyên nghiệp.',
    'Image' => 'img/products/lining-windstorm-72.jpg',
    'Price' => 299000,
    'Discount_price' => 279000,
    'Quantity' => 20,
    'Status' => true,
    'Created_at' => now(),
    'updated_at' => now(), // ✅ Đúng

],
[
    'Categories_ID' => 1,
    'Name' => 'Li‑Ning Turbo Charging 75',
    'SKU' => 'LINING-TC75',
    'Brand' => 'Lining',
    'SLUG' => 'lining-turbo-charging-75',
    'Description' => 'Turbo Charging 75 – cân bằng giữa lực smash và linh hoạt, đầy đủ công nghệ aerodynamic frame.',
    'Image' => 'img/products/lining-turbo-charging-75.jpg',
    'Price' => 239000,
    'Discount_price' => 219000,
    'Quantity' => 18,
    'Status' => true,
    'Created_at' => now(),
    'updated_at' => now(), // ✅ Đúng

],
[
    'Categories_ID' => 1,
    'Name' => 'Li‑Ning Aeronaut 9000',
    'SKU' => 'LINING-A9000',
    'Brand' => 'Lining',
    'SLUG' => 'lining-aeronaut-9000',
    'Description' => 'Aeronaut 9000 – thiết kế khí động tối ưu, giúp đẩy nhanh tốc độ swing.',
    'Image' => 'img/products/lining-aeronaut-9000.jpg',
    'Price' => 199000,
    'Discount_price' => 189000,
    'Quantity' => 22,
    'Status' => true,
    'Created_at' => now(),
    'updated_at' => now(), // ✅ Đúng

],
[
    'Categories_ID' => 1,
    'Name' => 'Li‑Ning G‑Force Superlite',
    'SKU' => 'LINING-GFS-1',
    'Brand' => 'Lining',
    'SLUG' => 'lining-g‑force-superlite',
    'Description' => 'G‑Force Superlite – siêu nhẹ, dễ điều khiển, phù hợp người mới và phong trào.',
    'Image' => 'img/products/lining-gforce-superlite.jpg',
    'Price' => 179000,
    'Discount_price' => 159000,
    'Quantity' => 30,
    'Status' => true,
    'Created_at' => now(),
    'updated_at' => now(), // ✅ Đúng

],
[
    'Categories_ID' => 1,
    'Name' => 'Li‑Ning Turbo Charging N7 II',
    'SKU' => 'LINING-TC-N7II',
    'Brand' => 'Lining',
    'SLUG' => 'lining-turbo-charging-n7‑ii',
    'Description' => 'Turbo Charging N7 II – phiên bản đột phá với lực power boost mạnh mẽ.',
    'Image' => 'img/products/lining-tc-n7ii.jpg',
    'Price' => 249000,
    'Discount_price' => 229000,
    'Quantity' => 17,
    'Status' => true,
    'Created_at' => now(),
    'updated_at' => now(), // ✅ Đúng

],
[
    'Categories_ID' => 1,
    'Name' => 'Li‑Ning Calibar 9000',
    'SKU' => 'LINING-CALIBAR-9000',
    'Brand' => 'Lining',
    'SLUG' => 'lining-calibar-9000',
    'Description' => 'Calibar 9000 – thiết kế head‑heavy, hỗ trợ cú smash mạnh mẽ.',
    'Image' => 'img/products/lining-calibar-9000.jpg',
    'Price' => 179000,
    'Discount_price' => 169000,
    'Quantity' => 20,
    'Status' => true,
    'Created_at' => now(),
    'updated_at' => now(), // ✅ Đúng

],
[
    'Categories_ID' => 1,
    'Name' => 'Li‑Ning Ultra Strong 9000',
    'SKU' => 'LINING-US9000',
    'Brand' => 'Lining',
    'SLUG' => 'lining-ultra-strong-9000',
    'Description' => 'Ultra Strong 9000 – khung cứng, hỗ trợ lực đánh mạnh bạo.',
    'Image' => 'img/products/lining-ultra-strong-9000.jpg',
    'Price' => 189000,
    'Discount_price' => 179000,
    'Quantity' => 15,
    'Status' => true,
    'Created_at' => now(),
    'updated_at' => now(), // ✅ Đúng

],
[
    'Categories_ID' => 1,
    'Name' => 'Li‑Ning Smash XP 80',
    'SKU' => 'LINING-XP80',
    'Brand' => 'Lining',
    'SLUG' => 'lining-smash-xp-80',
    'Description' => 'Smash XP 80 – chuyên dùng cho cầu lông trường học và phong trào.',
    'Image' => 'img/products/lining-smash-xp-80.jpg',
    'Price' => 129000,
    'Discount_price' => 119000,
    'Quantity' => 35,
    'Status' => true,
    'Created_at' => now(),
    'updated_at' => now(), // ✅ Đúng

],
[
    'Categories_ID' => 1,
    'Name' => 'Li‑Ning Flame 360',
    'SKU' => 'LINING-FLAME-360',
    'Brand' => 'Lining',
    'SLUG' => 'lining-flame-360',
    'Description' => 'Flame 360 – thiết kế màu sắc năng động, phù hợp cho mọi đối tượng.',
    'Image' => 'img/products/lining-flame-360.jpg',
    'Price' => 139000,
    'Discount_price' => 129000,
    'Quantity' => 28,
    'Status' => true,
    'Created_at' => now(),
    'updated_at' => now(), // ✅ Đúng

],
[
    'Categories_ID' => 1,
    'Name' => 'Li‑Ning AX FORCE 90',
    'SKU' => 'LINING-AXF90',
    'Brand' => 'Lining',
    'SLUG' => 'lining-ax-force-90',
    'Description' => 'AX FORCE 90 – cân bằng công thủ, phù hợp đánh đơn và đôi.',
    'Image' => 'img/products/lining-ax-force-90.jpg',
    'Price' => 159000,
    'Discount_price' => 149000,
    'Quantity' => 24,
    'Status' => true,
    'Created_at' => now(),
    'updated_at' => now(), // ✅ Đúng

],
[
    'Categories_ID' => 1,
    'Name' => 'Victor Thruster K 9000',
    'SKU' => 'VICTOR-K9000',
    'Brand' => 'Victor',
    'SLUG' => 'victor-thruster-k-9000',
    'Description' => 'Thruster K 9000 – vợt tấn công mạnh mẽ, đầu nặng, sân đơn ưu tiên smash.',
    'Image' => 'img/products/victor-k9000.jpg',
    'Price' => 249000,
    'Discount_price' => 229000,
    'Quantity' => 20,
    'Status' => true,
    'Created_at' => now(),
    'updated_at' => now(), // ✅ Đúng

],
[
    'Categories_ID' => 1,
    'Name' => 'Victor Thruster F 991',
    'SKU' => 'VICTOR-F991',
    'Brand' => 'Victor',
    'SLUG' => 'victor-thruster-f-991',
    'Description' => 'Thruster F 991 – đàn hồi cao, suitable cho người chơi trung – cao cấp.',
    'Image' => 'img/products/victor-f991.jpg',
    'Price' => 229000,
    'Discount_price' => 209000,
    'Quantity' => 18,
    'Status' => true,
    'Created_at' => now(),
    'updated_at' => now(), // ✅ Đúng

],
[
    'Categories_ID' => 1,
    'Name' => 'Victor Brave Sword 12',
    'SKU' => 'VICTOR-BS12',
    'Brand' => 'Victor',
    'SLUG' => 'victor-brave-sword-12',
    'Description' => 'Brave Sword 12 – siêu nhẹ, thân trung bình, kiểm soát nhanh.',
    'Image' => 'img/products/victor-bs12.jpg',
    'Price' => 189000,
    'Discount_price' => 169000,
    'Quantity' => 25,
    'Status' => true,
    'Created_at' => now(),
    'updated_at' => now(), // ✅ Đúng

],
[
    'Categories_ID' => 1,
    'Name' => 'Victor Brave Sword 12H',
    'SKU' => 'VICTOR-BS12H',
    'Brand' => 'Victor',
    'SLUG' => 'victor-brave-sword-12h',
    'Description' => 'Brave Sword 12H – phiên bản đầu nặng hơn để tăng lực smash.',
    'Image' => 'img/products/victor-bs12h.jpg',
    'Price' => 189000,
    'Discount_price' => 169000,
    'Quantity' => 22,
    'Status' => true,
    'Created_at' => now(),
    'updated_at' => now(), // ✅ Đúng

],
[
    'Categories_ID' => 1,
    'Name' => 'Victor Jetspeed S 12',
    'SKU' => 'VICTOR-JS12',
    'Brand' => 'Victor',
    'SLUG' => 'victor-jetspeed-s-12',
    'Description' => 'Jetspeed S 12 – tối ưu tốc độ swing, frame khí động học.',
    'Image' => 'img/products/victor-js12.jpg',
    'Price' => 199000,
    'Discount_price' => 179000,
    'Quantity' => 24,
    'Status' => true,
    'Created_at' => now(),
    'updated_at' => now(), // ✅ Đúng

],
[
    'Categories_ID' => 1,
    'Name' => 'Victor Jetspeed S 12II',
    'SKU' => 'VICTOR-JS12II',
    'Brand' => 'Victor',
    'SLUG' => 'victor-jetspeed-s-12ii',
    'Description' => 'Jetspeed S 12II – phiên bản nâng cấp nhẹ và nhanh hơn.',
    'Image' => 'img/products/victor-js12ii.jpg',
    'Price' => 206000,
    'Discount_price' => 189000,
    'Quantity' => 20,
    'Status' => true,
    'Created_at' => now(),
    'updated_at' => now(), // ✅ Đúng

],
[
    'Categories_ID' => 1,
    'Name' => 'Victor Auraspeed 90K',
    'SKU' => 'VICTOR-AS90K',
    'Brand' => 'Victor',
    'SLUG' => 'victor-auraspeed-90k',
    'Description' => 'Auraspeed 90K – nhẹ, tốc độ cao, thiết kế chơi đôi hiệu suất.',
    'Image' => 'img/products/victor-as90k.jpg',
    'Price' => 179000,
    'Discount_price' => 159000,
    'Quantity' => 30,
    'Status' => true,
    'Created_at' => now(),
    'updated_at' => now(), // ✅ Đúng

],
[
    'Categories_ID' => 1,
    'Name' => 'Victor Auraspeed 60K',
    'SKU' => 'VICTOR-AS60K',
    'Brand' => 'Victor',
    'SLUG' => 'victor-auraspeed-60k',
    'Description' => 'Auraspeed 60K – nhẹ, linh hoạt, dễ điều khiển.',
    'Image' => 'img/products/victor-as60k.jpg',
    'Price' => 159000,
    'Discount_price' => 149000,
    'Quantity' => 28,
    'Status' => true,
    'Created_at' => now(),
    'updated_at' => now(), // ✅ Đúng

],
[
    'Categories_ID' => 1,
    'Name' => 'Victor Hypernano X 900',
    'SKU' => 'VICTOR-HNX900',
    'Brand' => 'Victor',
    'SLUG' => 'victor-hypernano-x-900',
    'Description' => 'Hypernano X 900 – khung nhẹ và cứng, phù hợp tấn công nhanh.',
    'Image' => 'img/products/victor-hnx900.jpg',
    'Price' => 195000,
    'Discount_price' => 179000,
    'Quantity' => 18,
    'Status' => true,
    'Created_at' => now(),
    'updated_at' => now(), // ✅ Đúng

],
[
    'Categories_ID' => 1,
    'Name' => 'Victor Hypernano X 700',
    'SKU' => 'VICTOR-HNX700',
    'Brand' => 'Victor',
    'SLUG' => 'victor-hypernano-x-700',
    'Description' => 'Hypernano X 700 – hàng aggresive nhưng không quá nặng tay.',
    'Image' => 'img/products/victor-hnx700.jpg',
    'Price' => 175000,
    'Discount_price' => 159000,
    'Quantity' => 20,
    'Status' => true,
    'Created_at' => now(),
    'updated_at' => now(), // ✅ Đúng

],
[
    'Categories_ID' => 1,
    'Name' => 'Victor DriveX 9X B',
    'SKU' => 'VICTOR-DX9XB',
    'Brand' => 'Victor',
    'SLUG' => 'victor-drivex-9x-b',
    'Description' => 'DriveX 9X B – vợt cân bằng, thân cứng, kiểm soát tốt và hỗ trợ smash mạnh.',
    'Image' => 'img/products/victor-dx9xb.jpg',
    'Price' => 215000,
    'Discount_price' => 199000,
    'Quantity' => 18,
    'Status' => true,
    'Created_at' => now(),
    'updated_at' => now(), // ✅ Đúng

],
[
    'Categories_ID' => 1,
    'Name' => 'Victor DriveX 1L A',
    'SKU' => 'VICTOR-DX1LA',
    'Brand' => 'Victor',
    'SLUG' => 'victor-drivex-1l-a',
    'Description' => 'DriveX 1L A – nhẹ, linh hoạt, dành cho người mới chơi và phong trào.',
    'Image' => 'img/products/victor-dx1la.jpg',
    'Price' => 129000,
    'Discount_price' => 119000,
    'Quantity' => 24,
    'Status' => true,
    'Created_at' => now(),
    'updated_at' => now(), // ✅ Đúng

],
[
    'Categories_ID' => 1,
    'Name' => 'Victor Thruster K Falcon',
    'SKU' => 'VICTOR-TKFALCON',
    'Brand' => 'Victor',
    'SLUG' => 'victor-thruster-k-falcon',
    'Description' => 'Thruster K Falcon – vợt công cao cấp, đầu nặng, thân cứng, smash mạnh.',
    'Image' => 'img/products/victor-tkfalcon.jpg',
    'Price' => 269000,
    'Discount_price' => 255000,
    'Quantity' => 20,
    'Status' => true,
    'Created_at' => now(),
    'updated_at' => now(), // ✅ Đúng

],
[
    'Categories_ID' => 1,
    'Name' => 'Victor AuraSpeed 100X',
    'SKU' => 'VICTOR-AS100X',
    'Brand' => 'Victor',
    'SLUG' => 'victor-auraspeed-100x',
    'Description' => 'Auraspeed 100X – tốc độ nhanh, thân vừa, công nghệ tăng tốc swing frame.',
    'Image' => 'img/products/victor-as100x.jpg',
    'Price' => 249000,
    'Discount_price' => 235000,
    'Quantity' => 19,
    'Status' => true,
    'Created_at' => now(),
    'updated_at' => now(), // ✅ Đúng

],
[
    'Categories_ID' => 1,
    'Name' => 'Victor Brave Sword 1900',
    'SKU' => 'VICTOR-BS1900',
    'Brand' => 'Victor',
    'SLUG' => 'victor-brave-sword-1900',
    'Description' => 'Brave Sword 1900 – dòng phổ thông, thân trung bình, nhẹ dễ chơi.',
    'Image' => 'img/products/victor-bs1900.jpg',
    'Price' => 99000,
    'Discount_price' => 89000,
    'Quantity' => 30,
    'Status' => true,
    'Created_at' => now(),
    'updated_at' => now(), // ✅ Đúng

],
[
    'Categories_ID' => 1,
    'Name' => 'Victor JetSpeed S 10Q',
    'SKU' => 'VICTOR-JS10Q',
    'Brand' => 'Victor',
    'SLUG' => 'victor-jetspeed-s-10q',
    'Description' => 'Jetspeed S 10Q – kiểm soát tốt, đánh đôi hiệu quả, thân trung bình.',
    'Image' => 'img/products/victor-js10q.jpg',
    'Price' => 199000,
    'Discount_price' => 185000,
    'Quantity' => 22,
    'Status' => true,
    'Created_at' => now(),
    'updated_at' => now(), // ✅ Đúng

],
[
    'Categories_ID' => 1,
    'Name' => 'Victor Hypernano X Air',
    'SKU' => 'VICTOR-HNXAIR',
    'Brand' => 'Victor',
    'SLUG' => 'victor-hypernano-x-air',
    'Description' => 'Hypernano X Air – vợt siêu nhẹ, linh hoạt, phù hợp cho nữ và người mới.',
    'Image' => 'img/products/victor-hnxair.jpg',
    'Price' => 149000,
    'Discount_price' => 139000,
    'Quantity' => 26,
    'Status' => true,
    'Created_at' => now(),
    'updated_at' => now(), // ✅ Đúng

],
[
    'Categories_ID' => 1,
    'Name' => 'Victor Light Fighter 7000',
    'SKU' => 'VICTOR-LF7000',
    'Brand' => 'Victor',
    'SLUG' => 'victor-light-fighter-7000',
    'Description' => 'Light Fighter 7000 – siêu nhẹ, tốc độ cao, phù hợp phản tạt nhanh.',
    'Image' => 'img/products/victor-lf7000.jpg',
    'Price' => 185000,
    'Discount_price' => 169000,
    'Quantity' => 20,
    'Status' => true,
    'Created_at' => now(),
    'updated_at' => now(), // ✅ Đúng

],
[
    'Categories_ID' => 1,
    'Name' => 'Victor Meteor X 80',
    'SKU' => 'VICTOR-MX80',
    'Brand' => 'Victor',
    'SLUG' => 'victor-meteor-x-80',
    'Description' => 'Meteor X 80 – thân cứng, đầu nặng, smash uy lực cho người chơi tấn công.',
    'Image' => 'img/products/victor-mx80.jpg',
    'Price' => 209000,
    'Discount_price' => 195000,
    'Quantity' => 18,
    'Status' => true,
    'Created_at' => now(),
    'updated_at' => now(), // ✅ Đúng

],
[
    'Categories_ID' => 1,
    'Name' => 'Victor Spira 21',
    'SKU' => 'VICTOR-SP21',
    'Brand' => 'Victor',
    'SLUG' => 'victor-spira-21',
    'Description' => 'Spira 21 – mẫu vợt linh hoạt, thân trung bình, giá mềm, dễ làm quen.',
    'Image' => 'img/products/victor-sp21.jpg',
    'Price' => 115000,
    'Discount_price' => 105000,
    'Quantity' => 27,
    'Status' => true,
    'Created_at' => now(),
    'updated_at' => now(), // ✅ Đúng

],




        ];

        DB::table('products')->insert($products);
    }
}
