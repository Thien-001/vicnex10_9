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
        $userId = $request->query('user_id');
        $perPage = $request->query('per_page', 20);

        $notifications = Notification::where('User_ID', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return response()->json($notifications);
    }

    // Đánh dấu đã đọc 1 thông báo
    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->is_read = true;
        $notification->save();

        return response()->json(['success' => true]);
    }

    // Đánh dấu đã đọc nhiều thông báo
    public function markManyAsRead(Request $request)
    {
        $ids = $request->input('ids', []);
        Notification::whereIn('Notifications_ID', $ids)->update(['is_read' => true]);
        return response()->json(['success' => true]);
    }

    // Xóa 1 thông báo
    public function destroy($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->delete();

        return response()->json(['success' => true]);
    }

    // Xóa nhiều thông báo
    public function destroyMany(Request $request)
    {
        $ids = $request->input('ids', []);
        Notification::whereIn('Notifications_ID', $ids)->delete();
        return response()->json(['success' => true]);
    }
}
