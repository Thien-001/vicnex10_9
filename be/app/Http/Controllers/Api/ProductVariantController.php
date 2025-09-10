<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductVariant;

class ProductVariantController extends Controller
{
    public function index()
    {
        return response()->json([
            'data' => ProductVariant::all()
        ]);
    }
}
