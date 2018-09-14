<?php

namespace App\Http\Controllers\Chat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Message;

class ChatMessageController extends Controller
{
    /**
     * undocumented function
     *
     * @return void
     * @author
     **/
    public function index ()
    {
        $message = Message::with(['user'])->latest()->limit(10)->get();
        return response()->json($message, 200);
    }
}
