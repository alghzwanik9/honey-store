<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AIService;

class ChatController extends Controller
{
    protected $aiService;

    public function __construct(AIService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function sendMessage(Request $request)
    {
        // Increase execution time for handling AI requests
        set_time_limit(300);
        ini_set('max_execution_time', 300);
        ini_set('default_socket_timeout', 300);

        $request->validate([
            'message' => 'required|string|max:500',
        ]);

        try {
            $response = $this->aiService->askAssistant($request->input('message'));

            return response()->json([
                'reply' => $response
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('AI Chat Error: ' . $e->getMessage());
            return response()->json(['reply' => 'عذراً، حدث خطأ داخلي. حاول مرة أخرى لاحقاً.'], 500);
        }
    }
}
