<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Location;

class LocationController extends Controller
{
    public function index()
    {
        $locations = Location::latest()->paginate(10);
        return view('admin.location.index', compact('locations'));
    }

    public function create()
    {
        return view('admin.location.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'address'     => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Location::create($request->only(['name', 'address', 'description']));

        return redirect()->route('admin.locations.index')->with('success', 'Thêm địa điểm thành công!');
    }

    public function edit($id)
    {
        $location = Location::findOrFail($id);
        return view('admin.location.edit', compact('location'));
    }

    public function update(Request $request, $id)
    {
        $location = Location::findOrFail($id);

        $request->validate([
            'name'        => 'required|string|max:255',
            'address'     => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $location->update($request->only(['name', 'address', 'description']));

        return redirect()->route('admin.locations.index')->with('success', 'Cập nhật địa điểm thành công!');
    }

    public function destroy($id)
    {
        $location = Location::findOrFail($id);
        $location->delete();

        return redirect()->route('admin.locations.index')->with('success', 'Xóa địa điểm thành công!');
    }
}
