<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Court;
use App\Models\Location;
use Illuminate\Support\Facades\Storage;

class CourtController extends Controller
{
    // Hiển thị danh sách sân
    public function index()
    {
        $courts = Court::with('location')->latest()->paginate(10);
        return view('admin.courts.index', compact('courts'));
    }

    // Hiển thị form thêm sân mới
    public function create()
    {
        $locations = Location::all();
        return view('admin.courts.create', compact('locations'));
    }

    // Xử lý lưu sân mới
    public function store(Request $request)
    {
        $request->validate([
            'Name'            => 'required|string|max:255',
            'location_id'     => 'required|exists:locations,id',
            'Court_type'      => 'required|string|max:50',
            'Price_per_hour'  => 'required|numeric|min:0',
            'Image'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->only([
            'Name', 'location_id', 'Description', 'Court_type', 'Price_per_hour', 'Status'
        ]);

        if ($request->hasFile('Image')) {
            $file = $request->file('Image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/courts'), $filename);
            $data['Image'] = 'uploads/courts/' . $filename;
        }

        $data['Created_at'] = now();
        $data['Updated_at'] = now();

        Court::create($data);

        return redirect()->route('admin.courts.index')->with('success', 'Thêm sân thành công!');
    }

    // Hiển thị form sửa sân
    public function edit($id)
    {
        $court = Court::findOrFail($id);
        $locations = Location::all();
        return view('admin.courts.edit', compact('court', 'locations'));
    }

    // Cập nhật sân
    public function update(Request $request, $id)
    {
        $court = Court::findOrFail($id);

        $request->validate([
            'Name'            => 'required|string|max:255',
            'location_id'     => 'required|exists:locations,id',
            'Court_type'      => 'required|string|max:50',
            'Price_per_hour'  => 'required|numeric|min:0',
            'Image'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->only([
            'Name', 'location_id', 'Description', 'Court_type', 'Price_per_hour', 'Status'
        ]);

        if ($request->hasFile('Image')) {
            // Xóa ảnh cũ nếu tồn tại
            if ($court->Image && file_exists(public_path($court->Image))) {
                unlink(public_path($court->Image));
            }

            $file = $request->file('Image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/courts'), $filename);
            $data['Image'] = 'uploads/courts/' . $filename;
        }

        $data['Updated_at'] = now();

        $court->update($data);

        return redirect()->route('admin.courts.index')->with('success', 'Cập nhật sân thành công!');
    }

    // Xóa sân
    public function destroy($id)
    {
        $court = Court::findOrFail($id);

        if ($court->Image && file_exists(public_path($court->Image))) {
            unlink(public_path($court->Image));
        }

        $court->delete();

        return redirect()->route('admin.courts.index')->with('success', 'Xóa sân thành công!');
    }
}
