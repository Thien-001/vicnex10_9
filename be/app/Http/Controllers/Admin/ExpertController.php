<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expert;
use Illuminate\Http\Request;

class ExpertController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $experts = Expert::all();
        return view('admin.experts.index', compact('experts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.experts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'photo'    => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'bio'      => 'nullable|string',
        ]);
        Expert::create($data);
        return redirect()->route('admin.experts.index')->with('success', 'Thêm chuyên gia thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $expert = Expert::findOrFail($id);
        return view('admin.experts.show', compact('expert'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $expert = Expert::findOrFail($id);
        return view('admin.experts.edit', compact('expert'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $expert = Expert::findOrFail($id);
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'photo'    => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'bio'      => 'nullable|string',
        ]);
        $expert->update($data);
        return redirect()->route('admin.experts.index')->with('success', 'Cập nhật chuyên gia thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $expert = Expert::findOrFail($id);
        $expert->delete();
        return redirect()->route('admin.experts.index')->with('success', 'Xóa chuyên gia thành công!');
    }
}
