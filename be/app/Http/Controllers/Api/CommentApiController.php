<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;

class CommentApiController extends Controller
{
    // Lấy danh sách bình luận sản phẩm theo Product_ID
    public function index($id, Request $request)
    {
        // Nếu là bình luận sản phẩm
        if ($request->is('api/products/*/comments')) {
            $comments = Comment::with('user')
                ->where('Product_ID', $id)
                ->orderByDesc('Create_at')
                ->get();
        }
        // Nếu là bình luận bài viết
        else if ($request->is('api/posts/*/comments')) {
            $comments = Comment::with('user')
                ->where('Post_ID', $id)
                ->orderByDesc('Create_at')
                ->get();
        }
        return response()->json($comments);
    }

    // Thêm mới bình luận sản phẩm
    public function store($id, Request $request)
    {
        if ($request->is('api/products/*/comments')) {
            $validated = $request->validate([
                'User_ID'    => 'required|exists:user,ID',
                'Content'    => 'required|string',
                'Status'     => 'nullable|integer',
            ]);
            $comment = Comment::create([
                'Product_ID' => $id,
                'User_ID'    => $validated['User_ID'],
                'Content'    => $validated['Content'],
                'Status'     => $validated['Status'] ?? 1,
                'Create_at'  => now(),
                'Update_at'  => now(),
            ]);
        } else if ($request->is('api/posts/*/comments')) {
            $validated = $request->validate([
                'User_ID'    => 'required|exists:user,ID',
                'Content'    => 'required|string',
                'Status'     => 'nullable|integer',
            ]);
            $comment = Comment::create([
                'Post_ID'    => $id,
                'User_ID'    => $validated['User_ID'],
                'Content'    => $validated['Content'],
                'Status'     => $validated['Status'] ?? 1,
                'Create_at'  => now(),
                'Update_at'  => now(),
            ]);
        }
        $comment->load('user');
        return response()->json($comment, 201);
    }

    // Xóa bình luận
    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();

        return response()->json(['message' => 'Xóa bình luận thành công!']);
    }

    public function productComments($productId)
    {
        $comments = Comment::with('user')
            ->where('Product_ID', $productId)
            ->orderByDesc('Create_at')
            ->get();
        return response()->json($comments);
    }

    public function storeProductComment(Request $request, $productId)
    {
        $validated = $request->validate([
            'User_ID'    => 'required|exists:user,ID',
            'Content'    => 'required|string',
            'Status'     => 'nullable|integer',
        ]);
        $comment = Comment::create([
            'Product_ID' => $productId,
            'User_ID'    => $validated['User_ID'],
            'Content'    => $validated['Content'],
            'Status'     => $validated['Status'] ?? 1,
            'Create_at'  => now(),
            'Update_at'  => now(),
        ]);
        $comment->load('user');
        return response()->json($comment, 201);
    }
}
