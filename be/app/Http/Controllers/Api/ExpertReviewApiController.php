<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ExpertReviewApiController extends Controller
{
    public function index(Request $request)
    {
        $productId = $request->query('product_id');
        $query = \App\Models\ExpertReview::leftJoin('experts', 'expert_reviews.expert_name', '=', 'experts.name')
            ->select(
                'expert_reviews.*',
                'experts.position as position',
                'experts.photo as expert_image'
            );

        if ($productId) {
            $query->where('expert_reviews.product_id', $productId);
        }

        $reviews = $query->get();

        return response()->json($reviews);
    }
}
