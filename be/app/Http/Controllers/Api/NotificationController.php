<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    // Lấy danh sách thông báo của user (có phân trang)
    public function index(Request $request)
    {
        // Nếu dùng auth, lấy từ $request->user()->id, nếu không thì lấy từ query
        $userId = $request->user() ? $request->user()->id : $request->query('user_id');
        $perPage = $request->query('per_page', 20);

        $notifications = Notification::where('User_ID', $userId)
            ->orderBy('Created_at', 'desc')
            ->paginate($perPage);

        return response()->json($notifications);
    }

    // Đánh dấu đã đọc 1 thông báo
    public function markAsRead($id, Request $request)
    {
        $notification = Notification::findOrFail($id);
        // Nếu dùng auth, kiểm tra user
        if ($request->user() && $notification->User_ID != $request->user()->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $notification->is_read = 1;
        $notification->save();

        return response()->json(['success' => true]);
    }

    // Đánh dấu đã đọc nhiều thông báo
    public function markManyAsRead(Request $request)
    {
        $ids = $request->input('ids', []);
        if ($request->user()) {
            Notification::whereIn('Notifications_ID', $ids)
                ->where('User_ID', $request->user()->id)
                ->update(['is_read' => 1]);
        } else {
            Notification::whereIn('Notifications_ID', $ids)->update(['is_read' => 1]);
        }
        return response()->json(['success' => true]);
    }

    // Đánh dấu tất cả thông báo của user là đã đọc
    public function readAll(Request $request)
    {
        $userId = $request->user() ? $request->user()->id : $request->input('user_id');
        if (!$userId) {
            return response()->json(['error' => 'Thiếu user_id'], 400);
        }
        Notification::where('User_ID', $userId)->update(['is_read' => 1]);
        return response()->json(['success' => true]);
    }

    // Xóa 1 thông báo
    public function destroy($id, Request $request)
    {
        $notification = Notification::findOrFail($id);
        if ($request->user() && $notification->User_ID != $request->user()->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $notification->delete();

        return response()->json(['success' => true]);
    }

    // Xóa nhiều thông báo
    public function destroyMany(Request $request)
    {
        $ids = $request->input('ids', []);
        if ($request->user()) {
            Notification::whereIn('Notifications_ID', $ids)
                ->where('User_ID', $request->user()->id)
                ->delete();
        } else {
            Notification::whereIn('Notifications_ID', $ids)->delete();
        }
        return response()->json(['success' => true]);
    }

    // Tạo mới thông báo (cho admin hoặc hệ thống)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'User_ID' => 'required|integer',
            'Title' => 'required|string|max:255',
            'Message' => 'required|string|max:255',
            'Type' => 'nullable|string|max:50',
            'link' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:50',
            'priority' => 'nullable|string|max:20',
        ]);

        $notify = Notification::create(array_merge($validated, ['is_read' => 0]));
        return response()->json($notify, 201);
    }
}
