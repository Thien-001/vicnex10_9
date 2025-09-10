<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;

class BannerApiController extends Controller
{
    /**
     * Trả về danh sách banner đang active, sắp xếp theo position.
     */
    public function index()
    {
        return response()->json(
            Banner::where('is_active', 1)->orderBy('position')->get()
        );
    }
}