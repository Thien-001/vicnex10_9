<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactMessage;

class ContactMessageController extends Controller
{
    public function index()
    {
        $contacts = ContactMessage::orderBy('Created_at', 'desc')->paginate(15);
        return view('admin.contact.index', compact('contacts'));
    }
    public function edit($id)
    {
        $contact = ContactMessage::findOrFail($id);
        return view('admin.contact.edit', compact('contact'));
    }
    public function update(Request $request, $id)
{
    $request->validate([
        'Note' => 'nullable|string',
        'Status' => 'required|in:0,1', // đảm bảo chỉ nhận 0 hoặc 1
    ]);

    $contact = ContactMessage::findOrFail($id);
    $contact->Note = $request->input('Note');
    $contact->Status = $request->input('Status'); // ✅ thêm dòng này
    $contact->save();

    return redirect()->route('admin.contact.index')->with('success', 'Cập nhật liên hệ thành công.');
}

}
