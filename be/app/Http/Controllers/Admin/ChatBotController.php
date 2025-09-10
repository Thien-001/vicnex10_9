<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatBotController extends Controller
{
    public function chatBadminton(Request $request)
    {
        $message = $request->input('message');
        $apiKey = env('DEEPSEEK_API_KEY'); // Đổi sang DeepSeek

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ])->post('https://api.deepseek.com/v1/chat/completions', [
            'model' => 'deepseek-chat',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'Bạn là chuyên gia tư vấn cầu lông Vicnex, hãy trả lời ngắn gọn, thân thiện, dễ hiểu, ưu tiên thông tin về cầu lông, sản phẩm, kỹ thuật, luật chơi, tin tức mới nhất.'
                ],
                [
                    'role' => 'user',
                    'content' => $message
                ]
            ],
            'max_tokens' => 300,
            'temperature' => 0.7,
        ]);

        $data = $response->json();
        \Log::info('DeepSeek response:', $data); // Ghi log để debug

        $reply = $data['choices'][0]['message']['content'] ?? 'Xin lỗi, tôi chưa trả lời được câu hỏi này.';

        return response()->json([
            'reply' => $reply
        ]);
    }
}

