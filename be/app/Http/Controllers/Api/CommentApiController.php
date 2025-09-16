<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PostComment;

class CommentApiController extends Controller
{
    // Lấy bình luận bài viết
    public function postComments($postId)
    {
        $comments = \App\Models\PostComment::with('user')
            ->where('Post_ID', $postId)
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($comments);
    }

    // Thêm bình luận bài viết
    public function storePostComment(Request $request, $postId)
    {
        \Log::info('Dữ liệu nhận được:', $request->all());

        try {
            $validated = $request->validate([
                'User_ID' => 'required|exists:user,ID',
                'text'    => 'required|string',
            ]);
            $validated['Post_ID'] = $postId;
            $comment = \App\Models\PostComment::create($validated);

            return response()->json([
                'message' => 'Tạo bình luận thành công',
                'comment' => $comment
            ], 201);
        } catch (\Exception $e) {
            \Log::error('Lỗi bình luận:', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Bình luận thất bại!',
                'error' => $e->getMessage()
            ], 400);
        }
    }
}
