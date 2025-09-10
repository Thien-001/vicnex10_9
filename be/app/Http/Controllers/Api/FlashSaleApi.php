<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FlashSale;

class FlashSaleApi extends Controller
{
    // Lấy tất cả Flash Sale có status = 1, không kiểm tra thời gian
    public function index()
    {
        $flashSales = \App\Models\FlashSale::with('product')
            ->where('status', 1)
            ->where('is_show', 1) // chỉ lấy flash sale đang hiển thị
            ->get();

        return response()->json($flashSales);
    }
}