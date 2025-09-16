<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PostComment;

class PostCommentApi extends Controller
{
    // GET /api/comments
    public function index()
    {
        return response()->json(PostComment::all(), 200);
    }

    // POST /api/comments
    public function store(Request $request)
    {
        $data = $request->validate([
            'Post_ID'  => 'required|exists:posts,Post_ID',
            'User_ID'  => 'required|exists:users,ID',
            'text'     => 'required|string',
        ]);

        $comment = PostComment::create($data);

        return response()->json([
            'message' => 'Tạo bình luận thành công',
            'comment' => $comment
        ], 201);
    }

    // GET /api/comments/{id}
    public function show($id)
    {
        $comment = PostComment::findOrFail($id);
        return response()->json($comment);
    }

    // PUT/PATCH /api/comments/{id}
    public function update(Request $request, $id)
    {
        $comment = PostComment::findOrFail($id);

        $data = $request->validate([
            'text' => 'sometimes|required|string',
        ]);

        $comment->update($data);

        return response()->json([
            'message' => 'Cập nhật thành công',
            'data' => $comment
        ]);
    }

    // DELETE /api/comments/{id}
    public function destroy($id)
    {
        $comment = PostComment::findOrFail($id);
        $comment->delete();

        return response()->json(['message' => 'Xóa thành công'], 200);
    }

    // GET /api/posts/{post}/comments
    public function postComments($postId)
    {
        $comments = PostComment::where('Post_ID', $postId)
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($comments);
    }

    // POST /api/posts/{post}/comments
    public function storePostComment(Request $request, $postId)
    {
        \Log::info('Dữ liệu nhận từ FE:', $request->all());

        $data = $request->validate([
            'User_ID'  => 'required|exists:users,ID',
            'text'     => 'required|string',
        ]);
        $data['Post_ID'] = $postId;

        $comment = PostComment::create($data);

        return response()->json([
            'message' => 'Tạo bình luận thành công',
            'comment' => $comment
        ], 201);
    }
}
