<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;

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
    public function index()
    {
        $messages = [
            [
                'created_at' => '2020-09-11 12:13:14',
                'user'       => [
                    'name' => 'alex',
                ],
                'message'    => 'msg 1',
            ],
        ];

        return view('chat', ['messages' => $messages]);
    }
}
