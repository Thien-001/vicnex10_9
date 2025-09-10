<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;

class PostCommentApi extends Controller
{
    // GET /api/comments
    public function index()
    {
        return response()->json(Comment::all(), 200);
    }

    // POST /api/comments
    public function store(Request $request)
{
    $data = $request->validate([
        'Post_ID'  => 'required|exists:posts,Post_ID',
        'User_ID'  => 'required|exists:user,ID',
        'Content'  => 'required|string',
        'Status'   => 'nullable|boolean',
    ]);

    $data['Create_at'] = now();

    $comment = Comment::create($data);

    // Load thêm thông tin người dùng bình luận
    $comment->load('user');

    return response()->json([
        'message' => 'Tạo bình luận thành công',
        'data'    => $comment
    ], 201);
}


    // GET /api/comments/{id}
    public function show($id)
    {
        $comment = Comment::findOrFail($id);
        return response()->json($comment);
    }

    // PUT/PATCH /api/comments/{id}
    public function update(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);

        $data = $request->validate([
            'Content' => 'sometimes|required|string',
            'Status'  => 'nullable|boolean',
        ]);

        $data['Update_at'] = now();
        $comment->update($data);

        return response()->json([
            'message' => 'Cập nhật thành công',
            'data' => $comment
        ]);
    }

    // DELETE /api/comments/{id}
    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();

        return response()->json(['message' => 'Xóa thành công'], 200);
    }
}
