<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    /**
     * Hiển thị danh sách banner.
     */
    public function index()
    {
        $banners = Banner::all();
        return view('admin.banner.index', compact('banners'));
    }

    /**
     * Hiển thị form thêm banner mới.
     */
    public function create()
    {
        return view('admin.banner.create');
    }

    /**
     * Lưu banner mới vào database.
     */
    public function store(Request $request)
    {
        $data = $request->all();

        // Xử lý upload ảnh nếu có
        if ($request->hasFile('image_url')) {
            $file = $request->file('image_url');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = 'uploads/banners/' . $filename;

            // Di chuyển file trực tiếp vào thư mục public
            $file->move(public_path('uploads/banners'), $filename);

            $data['image_url'] = $path;
        }


        // Xử lý checkbox is_active
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        Banner::create($data);

        return redirect()->route('admin.banner.index')->with('success', 'Thêm banner thành công!');
    }

    /**
     * Hiển thị form sửa banner.
     */
    public function edit($id)
    {
        $banner = Banner::findOrFail($id);
        return view('admin.banner.edit', compact('banner'));
    }

    /**
     * Cập nhật banner.
     */
    public function update(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);
        $data = $request->all();

        // Xử lý upload ảnh nếu có
        if ($request->hasFile('image_url')) {
            $file = $request->file('image_url');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = 'uploads/banners/' . $filename;

            // Di chuyển file trực tiếp vào thư mục public
            $file->move(public_path('uploads/banners'), $filename);

            $data['image_url'] = $path;
        }


        // Xử lý checkbox is_active
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        $banner->update($data);

        return redirect()->route('admin.banner.index')->with('success', 'Cập nhật banner thành công!');
    }

    /**
     * Xóa banner.
     */
    public function destroy($id)
    {
        $banner = Banner::findOrFail($id);
        $banner->delete();

        return redirect()->route('admin.banner.index')->with('success', 'Xóa banner thành công!');
    }

    /**
     * Hiển thị ảnh banner.
     */
    public function image($id)
    {
        $banner = Banner::findOrFail($id);
        $path = storage_path('app/public/' . $banner->image_url);

        if (!file_exists($path)) {
            abort(404);
        }

        // Thêm header CORS thủ công nếu cần cho frontend React
        return response()->file($path, [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, OPTIONS',
            'Access-Control-Allow-Headers' => 'Origin, Content-Type, Accept, Authorization, X-Request-With',
        ]);
    }
}
