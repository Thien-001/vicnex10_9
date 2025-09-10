<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationApi extends Controller
{
    // GET /api/locations
    public function index()
    {
        $locations = Location::withCount('courts')->get();
        return response()->json(['data' => $locations]);
    }

    // GET /api/locations/{id}/courts
    public function courts($id)
    {
        $courts = Location::findOrFail($id)->courts;
        return response()->json(['data' => $courts]);
    }
}
