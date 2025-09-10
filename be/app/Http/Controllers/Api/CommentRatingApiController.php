<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CommentRating;
use Illuminate\Http\Request;

class CommentRatingApiController extends Controller
{
    // Đánh giá (like/dislike) cho 1 bình luận
    public function store(Request $request, $commentId)
    {
        $request->validate([
            'User_ID' => 'required|exists:users,ID',
            'Type' => 'required|boolean', // 1: Like, 0: Dislike
        ]);

        // Mỗi user chỉ được vote 1 lần cho 1 comment
        $rating = CommentRating::updateOrCreate(
            ['Comment_ID' => $commentId, 'User_ID' => $request->User_ID],
            ['Type' => $request->Type]
        );

        return response()->json($rating, 201);
    }

    // Lấy tổng số like/dislike của 1 bình luận
    public function count($commentId)
    {
        $up = CommentRating::where('Comment_ID', $commentId)->where('Type', 1)->count();
        $down = CommentRating::where('Comment_ID', $commentId)->where('Type', 0)->count();
        return response()->json(['up' => $up, 'down' => $down]);
    }
}