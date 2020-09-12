<?php

namespace App\Http\Controllers;

use App\Events\ChatMessage;
use App\Models\Message;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function index(): Renderable
    {
        $latMessageIds = Message::orderBy('created_at', 'desc')
            ->take(5)
            ->pluck('id');

        $messages = Message::with('user')
            ->orderBy('created_at', 'asc')
            ->whereIn('id', $latMessageIds)
            ->get();

        return view('chat', ['messages' => $messages]);
    }

    public function message(Request $request)
    {
        $msg = Message::create([
            'user_id' => Auth::id(),
            'message' => $request->input('message'),
        ]);
        /** @var Message $message */
        $message = Message::with('user')->find($msg->id);

        broadcast(new ChatMessage($message))->toOthers();

        return response($message);
    }
}
