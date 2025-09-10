<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;

class StatisticsController extends Controller
{

    public function indexrevenue(Request $request)
{
    $labels = [];
    $orderRevenue = [];
    $bookingRevenue = [];
    $totalRevenue = [];

    $compareMode = false;
    $compareLabels = [];
    $compareOrderRevenue = [];
    $compareBookingRevenue = [];
    $compareTotalRevenue = [];

    // ✅ Nếu người dùng chọn 2 tháng
    if ($request->filled(['month1', 'year1', 'month2', 'year2'])) {
        $compareMode = true;

        $inputs = [
            ['month' => $request->month1, 'year' => $request->year1],
            ['month' => $request->month2, 'year' => $request->year2],
        ];

        foreach ($inputs as $input) {
            $month = $input['month'];
            $year = $input['year'];

            $label = "Tháng $month/$year";
            $compareLabels[] = $label;

            $order = DB::table('orders')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->sum('total_amount');

            $booking = DB::table('court_booking')
                ->whereYear('Booking_date', $year)
                ->whereMonth('Booking_date', $month)
                ->sum('Total_price');

            $compareOrderRevenue[] = $order;
            $compareBookingRevenue[] = $booking;
            $compareTotalRevenue[] = $order + $booking;
        }
    } else {
        // ✅ Mặc định: hiển thị 12 tháng hiện tại
        $currentYear = now()->year;

        for ($i = 1; $i <= 12; $i++) {
            $labels[] = "Tháng $i";
            $orderRevenue[$i] = 0;
            $bookingRevenue[$i] = 0;
            $totalRevenue[$i] = 0;
        }

        $orderStats = DB::table('orders')
            ->selectRaw('MONTH(created_at) as month, SUM(total_amount) as revenue')
            ->whereYear('created_at', $currentYear)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->get();

        foreach ($orderStats as $stat) {
            $orderRevenue[$stat->month] = $stat->revenue;
        }

        $bookingStats = DB::table('court_booking')
            ->selectRaw('MONTH(Booking_date) as month, SUM(Total_price) as revenue')
            ->whereYear('Booking_date', $currentYear)
            ->groupBy(DB::raw('MONTH(Booking_date)'))
            ->get();

        foreach ($bookingStats as $stat) {
            $bookingRevenue[$stat->month] = $stat->revenue;
        }

        for ($i = 1; $i <= 12; $i++) {
            $totalRevenue[$i] = $orderRevenue[$i] + $bookingRevenue[$i];
        }
    }

    // ✅ Truyền tất cả về 1 view
    return view('admin.statistics.revenue', [
        'compareMode' => $compareMode,

        // Dữ liệu cho chế độ so sánh
        'compareLabels' => $compareLabels,
        'compareOrderRevenue' => $compareOrderRevenue,
        'compareBookingRevenue' => $compareBookingRevenue,
        'compareTotalRevenue' => $compareTotalRevenue,

        // Dữ liệu mặc định 12 tháng
        'labels' => $labels,
        'orderRevenue' => array_values($orderRevenue),
        'bookingRevenue' => array_values($bookingRevenue),
        'totalRevenue' => array_values($totalRevenue),
    ]);
}



public function indexorder(Request $request)
{
    $month1 = $request->input('month1');
    $year1 = $request->input('year1');
    $month2 = $request->input('month2');
    $year2 = $request->input('year2');

    $labels = [];
    $totalAmount = [];
    $orderCounts = [];

    if ($month1 && $year1 && $month2 && $year2) {
        // So sánh 2 mốc thời gian

        // Mốc 1
        $data1 = DB::table('orders')
            ->selectRaw('COUNT(*) as total_orders, SUM(total_amount) as total_revenue')
            ->whereYear('created_at', $year1)
            ->whereMonth('created_at', $month1)
            ->first();

        $labels[] = "Tháng $month1/$year1";
        $totalAmount[] = $data1->total_revenue ?? 0;
        $orderCounts[] = $data1->total_orders ?? 0;

        // Mốc 2
        $data2 = DB::table('orders')
            ->selectRaw('COUNT(*) as total_orders, SUM(total_amount) as total_revenue')
            ->whereYear('created_at', $year2)
            ->whereMonth('created_at', $month2)
            ->first();

        $labels[] = "Tháng $month2/$year2";
        $totalAmount[] = $data2->total_revenue ?? 0;
        $orderCounts[] = $data2->total_orders ?? 0;

    } else {
        // Mặc định: hiển thị 12 tháng của năm hiện tại
        $currentYear = now()->year;

        $monthlyStats = DB::table('orders')
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as total_orders, SUM(total_amount) as total_revenue')
            ->whereYear('created_at', $currentYear)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy(DB::raw('MONTH(created_at)'))
            ->get();

        for ($i = 1; $i <= 12; $i++) {
            $labels[] = "Tháng $i";
            $totalAmount[$i] = 0;
            $orderCounts[$i] = 0;
        }

        foreach ($monthlyStats as $stat) {
            $totalAmount[$stat->month] = $stat->total_revenue;
            $orderCounts[$stat->month] = $stat->total_orders;
        }

        $totalAmount = array_values($totalAmount);
        $orderCounts = array_values($orderCounts);
    }

    return view('admin.statistics.order', compact('labels', 'totalAmount', 'orderCounts'));
}




public function indexbooking(Request $request)
{
    $month1 = $request->input('month1');
    $year1 = $request->input('year1');
    $month2 = $request->input('month2');
    $year2 = $request->input('year2');

    $labels = [];
    $courtBookingCounts = [];
    $courtBookingRevenue = [];

    if ($month1 && $year1 && $month2 && $year2) {
        // Khi có 2 mốc thời gian
        $timePoints = [
            ['label' => "Tháng $month1/$year1", 'month' => $month1, 'year' => $year1],
            ['label' => "Tháng $month2/$year2", 'month' => $month2, 'year' => $year2],
        ];

        foreach ($timePoints as $point) {
            $labels[] = $point['label'];
            $count = DB::table('court_booking')
                ->whereYear('Booking_date', $point['year'])
                ->whereMonth('Booking_date', $point['month'])
                ->count();

            $revenue = DB::table('court_booking')
                ->whereYear('Booking_date', $point['year'])
                ->whereMonth('Booking_date', $point['month'])
                ->sum('Total_price');

            $courtBookingCounts[] = $count;
            $courtBookingRevenue[] = $revenue;
        }

    } else {
        // Không có filter, hiển thị 12 tháng hiện tại
        $currentYear = now()->year;
        $monthlyCourtStats = DB::table('court_booking')
            ->selectRaw('MONTH(Booking_date) as month, COUNT(*) as total_bookings, SUM(Total_price) as total_booking_revenue')
            ->whereYear('Booking_date', $currentYear)
            ->groupBy(DB::raw('MONTH(Booking_date)'))
            ->get();

        for ($i = 1; $i <= 12; $i++) {
            $labels[] = "Tháng $i";
            $courtBookingCounts[$i] = 0;
            $courtBookingRevenue[$i] = 0;
        }

        foreach ($monthlyCourtStats as $stat) {
            $courtBookingCounts[$stat->month] = $stat->total_bookings;
            $courtBookingRevenue[$stat->month] = $stat->total_booking_revenue;
        }

        $courtBookingCounts = array_values($courtBookingCounts);
        $courtBookingRevenue = array_values($courtBookingRevenue);
    }

    return view('admin.statistics.booking', [
        'labels' => $labels,
        'courtBookingCounts' => $courtBookingCounts,
        'courtBookingRevenue' => $courtBookingRevenue,
    ]);
}


public function indexproduct(Request $request)
{
    $month1 = $request->input('month1');
    $year1 = $request->input('year1');
    $product1 = $request->input('product1');

    $month2 = $request->input('month2');
    $year2 = $request->input('year2');
    $product2 = $request->input('product2');

    // Danh sách sản phẩm
    $productList = DB::table('products')->pluck('Name', 'Product_ID');

    // Nếu có lọc, chỉ lấy thống kê và chart theo dữ liệu lọc
    $filteredStats = DB::table('products')
    ->when($product1 || $product2, function ($query) use ($product1, $product2) {
        // Lọc theo sản phẩm nếu có chọn
        $query->whereIn('products.Product_ID', array_filter([$product1, $product2]));
    })
    ->leftJoin('order_detail', function ($join) use ($month1, $year1, $month2, $year2) {
        $join->on('products.Product_ID', '=', 'order_detail.Product_ID')
            ->where(function ($query) use ($month1, $year1, $month2, $year2) {
                if ($month1 && $year1) {
                    $query->orWhere(function ($q) use ($month1, $year1) {
                        $q->whereMonth('order_detail.create_at', $month1)
                          ->whereYear('order_detail.create_at', $year1);
                    });
                }
                if ($month2 && $year2) {
                    $query->orWhere(function ($q) use ($month2, $year2) {
                        $q->whereMonth('order_detail.create_at', $month2)
                          ->whereYear('order_detail.create_at', $year2);
                    });
                }
            });
    })
    ->select(
        'products.Product_ID',
        'products.Name as product_name',
        DB::raw('COALESCE(SUM(order_detail.quantity), 0) as total_sold'),
        DB::raw('COALESCE(SUM(order_detail.total_price), 0) as total_revenue')
    )
    ->groupBy('products.Product_ID', 'products.Name')
    ->get();


    // Biểu đồ: chỉ lấy 2 sản phẩm được chọn (nếu có)
    $chartData = collect();

    if ($product1) {
        $chartData->push($filteredStats->firstWhere('Product_ID', $product1));
    }

    if ($product2 && $product2 !== $product1) {
        $chartData->push($filteredStats->firstWhere('Product_ID', $product2));
    }

    // Nếu không chọn gì, mặc định là top 10
    if ($chartData->isEmpty()) {
        $chartData = $filteredStats->sortByDesc('total_sold')->take(10);
    }

    $productNames = $chartData->pluck('product_name');
    $productSales = $chartData->pluck('total_sold');
    $productRevenue = $chartData->pluck('total_revenue');

    // Bảng so sánh
    $compareData = null;
    if ($month1 && $year1 && $month2 && $year2 && $product1 && $product2) {
        $compareData = [
            'month1' => $month1,
            'year1' => $year1,
            'month2' => $month2,
            'year2' => $year2,
            'product1' => DB::table('order_detail')
                ->whereMonth('create_at', $month1)
                ->whereYear('create_at', $year1)
                ->where('Product_ID', $product1)
                ->select(DB::raw('SUM(quantity) as total_sold'), DB::raw('SUM(total) as total_revenue'))
                ->first(),

            'product2' => DB::table('order_detail')
                ->whereMonth('create_at', $month2)
                ->whereYear('create_at', $year2)
                ->where('Product_ID', $product2)
                ->select(DB::raw('SUM(quantity) as total_sold'), DB::raw('SUM(total) as total_revenue'))
                ->first()
        ];
    }

    return view('admin.statistics.product', compact(
        'productNames',
        'productSales',
        'productRevenue',
        'filteredStats', // đổi tên cho dễ hiểu
        'productList',
        'compareData',
        'month1',
        'month2',
        'product1',
        'product2'
    ));
}




}
