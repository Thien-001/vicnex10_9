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
}
