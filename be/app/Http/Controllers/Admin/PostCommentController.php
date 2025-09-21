<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\PostComment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class PostCommentController extends Controller
{
    public function index()
    {
        $comments = PostComment::with('post', 'user')->paginate(10);
        return view('admin.postreview.index', compact('comments'));
    }
public function updateStatus(Request $request, $id)
{
    $comment = PostComment::findOrFail($id);
    $comment->Status = $request->input('status');
    $comment->Update_at = now();
    $comment->save();

    return redirect()->back()->with('success', 'Trạng thái bình luận đã được cập nhật.');
}

    public function create()
    {
        $posts = Post::all();
        $users = User::all();
        return view('comments.create', compact('posts', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'Post_ID' => 'required|exists:posts,Post_ID',
            'User_ID' => 'required|exists:user,ID',
            'Content' => 'required|string',
            'Status' => 'boolean',
        ]);

        PostComment::create($validated);
        return redirect()->route('comments.index')->with('success', 'Tạo bình luận thành công!');
    }

    public function show($id)
    {
        $comment = PostComment::with('post', 'user')->findOrFail($id);
        return view('comments.show', compact('comment'));
    }

    public function edit($id)
    {
        $comment = PostComment::findOrFail($id);
        $posts = Post::all();
        $users = User::all();
        return view('comments.edit', compact('comment', 'posts', 'users'));
    }

    public function update(Request $request, $id)
    {
        $comment = PostComment::findOrFail($id);

        $validated = $request->validate([
            'Post_ID' => 'required|exists:posts,Post_ID',
            'User_ID' => 'required|exists:user,ID',
            'Content' => 'required|string',
            'Status' => 'boolean',
        ]);

        $validated['Update_at'] = now();

        $comment->update($validated);
        return redirect()->route('comments.index')->with('success', 'Cập nhật bình luận thành công!');
    }

    public function destroy($id)
    {
        $comment = PostComment::findOrFail($id);
        $comment->delete();
        return redirect()->route('comments.index')->with('success', 'Xóa bình luận thành công!');
    }
}
