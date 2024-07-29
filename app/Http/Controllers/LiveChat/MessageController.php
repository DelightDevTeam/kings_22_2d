<?php

namespace App\Http\Controllers\LiveChat;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\LiveChat\Chat;
use App\Models\LiveChat\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        $chat = Chat::with('messages.sender')->find(1); // Example chat id

        return view('chat.index', ['messages' => $chat->messages]);
    }

    public function store(Request $request)
    {
        $message = Message::create([
            'chat_id' => $request->chat_id,
            'sender_id' => $request->sender_id,
            'message' => $request->message,
        ]);

        broadcast(new MessageSent($message))->toOthers();

        return response()->json($message, 201);
    }
}
