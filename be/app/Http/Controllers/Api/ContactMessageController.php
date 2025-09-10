<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactMessage;

class ContactMessageController extends Controller
{
    // Lưu liên hệ từ form contact
    public function store(Request $request)
    {
        $validated = $request->validate([
            'Name'    => 'required|string|max:255',
            'Email'   => 'required|email|max:255',
            'Phone'   => 'required|string|max:50',
            'Subject' => 'required|string|max:255',
            'Message' => 'required|string',
        ]);

        $contact = ContactMessage::create([
            'Name'    => $validated['Name'],
            'Email'   => $validated['Email'],
            'Phone'   => $validated['Phone'],
            'Subject' => $validated['Subject'],
            'Message' => $validated['Message'],
            'Status'  => 0,
            'Created_at' => now(),
            'Updated_at' => now(),
        ]);

        return response()->json(['success' => true, 'message' => 'Gửi liên hệ thành công!', 'data' => $contact]);
    }

    // (Tuỳ chọn) Lấy danh sách liên hệ cho admin
    public function index()
    {
        $contacts = ContactMessage::orderBy('Created_at', 'desc')->get();
        return response()->json($contacts);
    }
}
