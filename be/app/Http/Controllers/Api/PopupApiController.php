<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Popup;

class PopupApiController extends Controller
{
    public function index()
    {
        // Lấy popup đang active (có thể lấy popup mới nhất hoặc popup đầu tiên)
        $popup = Popup::where('is_active', 1)->latest()->first();
        return response()->json($popup);
    }
}
