<?php
// filepath: /home/titus/Documents/DATN/vicnex10_9/be/app/Http/Controllers/Api/ProductLineApiController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductLine;
use Illuminate\Http\Request;

class ProductLineApiController extends Controller
{
    public function index()
    {
        try {
            $lines = ProductLine::active()
                ->orderBy('brand')
                ->orderBy('name')
                ->get()
                ->groupBy('brand');
            
            return response()->json($lines);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getByBrand($brand)
    {
        try {
            $lines = ProductLine::active()
                ->byBrand($brand)
                ->orderBy('name')
                ->get();
            
            return response()->json($lines);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $line = ProductLine::with(['products' => function($query) {
                $query->where('Status', 1);
            }])->findOrFail($id);
            
            return response()->json($line);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}