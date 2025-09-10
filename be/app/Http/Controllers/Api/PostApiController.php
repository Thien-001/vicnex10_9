<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostApiController extends Controller
{
    public function index()
    {
        return response()->json(Post::with('user', 'category')->get(), 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'User_ID' => 'required|exists:user,ID',
            'Category_ID' => 'required|exists:post_categories,id',
            'Title' => 'required|string|max:255',
            'Thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'Content' => 'required',
            'Excerpt' => 'nullable|string',
            'Status' => 'required|in:1,0',
            'View' => 'nullable|integer',
        ]);

        $post = Post::create($validated);
        return response()->json($post, 201);
    }

    public function show($id)
    {
        $post = Post::with('user', 'category')->find($id);
        if (!$post) {
            return response()->json(['message' => 'Not found'], 404);
        }
        return response()->json($post, 200);
    }

    public function update(Request $request, $id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $validated = $request->validate([
            'User_ID' => 'required|exists:user,ID',
            'Category_ID' => 'required|exists:post_categories,id',
            'Title' => 'required|string|max:255',
            'Thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'Content' => 'required',
            'Excerpt' => 'nullable|string',
            'Status' => 'required|in:1,0',
            'View' => 'nullable|integer',
        ]);

        $validated['Updated_at'] = now();

        $post->update($validated);
        return response()->json($post, 200);
    }

    public function destroy($id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $post->delete();
        return response()->json(['message' => 'Deleted'], 200);
    }
}
