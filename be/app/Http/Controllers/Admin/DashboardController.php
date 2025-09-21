<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Thống kê tháng hiện tại
        $month = now()->month;
        $year = now()->year;

        $orderThisMonth = DB::table('orders')
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->count();

        $revenueThisMonth = DB::table('orders')
            ->where('status', 'completed')
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->sum('total_price');

        // Thống kê khách hàng mới trong tháng hiện tại
        $newUsers = DB::table('user')
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->count();

        // Thống kê lịch đặt sân mới trong tháng hiện tại
        // $newCourtBookings = DB::table('court_booking')
        //     ->whereMonth('create_at', $month)
        //     ->whereYear('create_at', $year)
        //     ->count();
        // 👉 Tổng tiền bán trong ngày (chỉ đơn completed)
            $todayRevenue = DB::table('orders')
                ->where('status', 'completed')
                ->whereDate('created_at', Carbon::today())
                ->sum('total_price');

        // Thống kê tổng số đơn hàng
        $totalOrders = DB::table('orders')->count();

        // Thống kê tổng số khách hàng
        $totalUsers = DB::table('user')->count();

        // Tổng số doanh thu
        $totalRevenue = DB::table('orders')
            ->where('status', 'completed')
            ->sum('total_price');

        // Tổng số lượt đặt sân
        $totalCourtBookings = DB::table('court_booking')->count();

        // Đơn hàng gần đây (5 đơn mới nhất) - PHẢI CÓ TRƯỜNG id
        $recentOrders = DB::table('orders')
            ->select('id', 'full_name', 'created_at', 'total_price', 'status')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        // Lịch đặt sân gần đây (5 lịch mới nhất) - PHẢI CÓ TRƯỜNG id
        $recentBookings = DB::table('court_booking')
            ->join('user', 'court_booking.user_id', '=', 'user.id')
            ->select(
                'court_booking.Court_booking_ID as id',
                'court_booking.created_at',
                'court_booking.Start_time',
                'court_booking.End_time',
                'court_booking.Total_price',
                'user.name as user_name',
                'user.Avatar'
            )
            ->orderByDesc('court_booking.created_at')
            ->limit(5)
            ->get();


$revenueByMonth = DB::table('orders')
    ->selectRaw('MONTH(created_at) as month, SUM(total_price) as total')
    ->whereYear('created_at', now()->year)
    ->where('status', 'completed')
    ->groupBy('month')
    ->pluck('total', 'month')
    ->toArray(); // ép về mảng

$ordersByMonth = DB::table('orders')
    ->selectRaw('MONTH(created_at) as month, COUNT(*) as total')
    ->whereYear('created_at', now()->year)
    ->groupBy('month')
    ->pluck('total', 'month')
    ->toArray(); // ép về mảng



$topProducts = DB::table('order_detail')
    ->join('products', 'order_detail.product_id', '=', 'products.product_id')
    ->select('products.name', DB::raw('SUM(order_detail.quantity) as total'))
    ->groupBy('products.product_id', 'products.name')
    ->orderByDesc('total')
    ->limit(5)
    ->get();


// $stockProducts = DB::table('products')
//     ->select('name', 'quantity') // giả sử cột tồn kho là quantity
//     ->where('quantity', '<', 10)
//     ->orderBy('quantity', 'asc') // sắp xếp tăng dần để thấy hàng nào gần hết
//     ->limit(5)
//     ->get();
$stockProducts = DB::table('products')
    ->leftJoin('product_variants', 'products.product_id', '=', 'product_variants.product_id')
    ->select(
        'products.product_id',
        'products.name',
        DB::raw('COALESCE(SUM(product_variants.quantity), products.quantity) as total_quantity')
    )
    ->groupBy('products.product_id', 'products.name', 'products.quantity')
    ->having('total_quantity', '<', 10)
    ->orderBy('total_quantity', 'asc')
    ->limit(5)
    ->get();




$orderStatusStats = DB::table('orders')
    ->select('status', DB::raw('COUNT(*) as total'))
    ->whereMonth('created_at', Carbon::now()->month)
    ->whereYear('created_at', Carbon::now()->year)
    ->groupBy('status')
    ->pluck('total', 'status');






        return view('admin.index', compact(
    'orderThisMonth',
    'revenueThisMonth',
    'newUsers',
    'todayRevenue',
    'totalOrders',
    'totalUsers',
    'totalRevenue',
    'totalCourtBookings',
    'recentOrders',
    'recentBookings',
    'revenueByMonth',
    'ordersByMonth',
    'topProducts',
    'stockProducts',
    'orderStatusStats'
));

    }
public function filter(Request $request)
{
    $request->validate([
        'from' => ['required','date'],
        'to'   => ['required','date','after_or_equal:from'],
    ]);

    $start = Carbon::parse($request->from)->startOfDay();
    $end   = Carbon::parse($request->to)->endOfDay();

    $base = DB::table('orders')->whereBetween('created_at', [$start, $end]);

    // Doanh thu theo ngày (chỉ đơn completed)
    $revenueByDate = (clone $base)
        ->where('status', 'completed')
        ->selectRaw('DATE(created_at) as d, SUM(total_price) as total')
        ->groupBy('d')
        ->orderBy('d')
        ->pluck('total','d')
        ->toArray();

    // Số đơn theo ngày
    $ordersByDate = (clone $base)
        ->selectRaw('DATE(created_at) as d, COUNT(*) as total')
        ->groupBy('d')
        ->orderBy('d')
        ->pluck('total','d')
        ->toArray();

    // Trạng thái trong khoảng
    $orderStatusStats = (clone $base)
        ->select('status', DB::raw('COUNT(*) as total'))
        ->groupBy('status')
        ->pluck('total','status')
        ->toArray();

    return response()->json([
        'revenueByDate'     => $revenueByDate,
        'ordersByDate'      => $ordersByDate,
        'orderStatusStats'  => $orderStatusStats,
    ]);
}

}
