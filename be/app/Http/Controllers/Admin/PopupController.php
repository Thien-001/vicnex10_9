<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Popup;
use Illuminate\Http\Request;

class PopupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $popups = Popup::all();
        return view('admin.popup.index', compact('popups'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.popup.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();

        if ($request->hasFile('image_url')) {
            $file = $request->file('image_url');
            $ext = $file->getClientOriginalExtension();
            $filename = uniqid() . '.' . $ext;

            // Lưu trực tiếp vào public/uploads/popups
            $file->move(public_path('uploads/popups'), $filename);

            // Lưu đường dẫn tương đối để dùng với asset()
            $data['image_url'] = 'uploads/popups/' . $filename;
        }


        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        Popup::create($data);

        return redirect()->route('admin.popup.index')->with('success', 'Thêm popup thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $popup = Popup::findOrFail($id);
        return view('admin.popup.show', compact('popup'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $popup = Popup::findOrFail($id);
        return view('admin.popup.edit', compact('popup'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $popup = Popup::findOrFail($id);
        $data = $request->all();

        if ($request->hasFile('image_url')) {
            $file = $request->file('image_url');
            $ext = $file->getClientOriginalExtension();
            $filename = uniqid() . '.' . $ext;

            // Lưu trực tiếp vào public/uploads/popups
            $file->move(public_path('uploads/popups'), $filename);

            // Lưu đường dẫn tương đối để dùng với asset()
            $data['image_url'] = 'uploads/popups/' . $filename;

        } else {
            unset($data['image_url']);
        }

        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        $popup->update($data);

        return redirect()->route('admin.popup.index')->with('success', 'Cập nhật popup thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $popup = Popup::findOrFail($id);
        $popup->delete();

        return redirect()->route('admin.popup.index')->with('success', 'Xóa popup thành công!');
    }
}
