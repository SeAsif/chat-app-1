<?php

namespace App\Http\Controllers\Chat;

use App\Events\Chat\MessageCreated;
use Illuminate\Http\Request;
use App\Http\Requests\Chat\StoreMessageRequest;
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

    /**
     * undocumented function
     *
     * @return void
     * @author
     **/
    public function store (StoreMessageRequest $request)
    {
        //return response(null, 500);
        $message = $request->user()->messages()->create([
            'body' => $request->body
        ]);

        broadcast(new MessageCreated($message))->toOthers();

        return response()->json($message, 200);
    }
}
