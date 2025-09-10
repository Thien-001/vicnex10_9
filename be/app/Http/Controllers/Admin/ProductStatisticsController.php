<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductStatisticsController extends Controller
{
    public function index(Request $request)
    {
        $from = $request->input('from_date', now()->subMonth()->toDateString());
        $to = $request->input('to_date', now()->toDateString());

        $products = Product::select('name')
            ->withSum(['orderDetails as total_sold' => function ($q) use ($from, $to) {
                $q->whereHas('order', function ($q2) use ($from, $to) {
                    $q2->where('status', 'completed')
                        ->whereBetween('created_at', [$from, $to]);
                });
            }], 'quantity')
            ->orderByDesc('total_sold')
            ->take(10)
            ->get();

        return view('admin.products.statistics', compact('products', 'from', 'to'));
    }
}
