<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Events\MessageSent;
use App\Rules\ReceiverIsFriend;

class ChatController extends Controller
{
    public function show(Request $request) {
        $this->validate(request(), [
            'otherUserId' => [
                'bail',
                function ($attribute, $value, $fail) {
                    $friends = Auth::user()->friends()->get();
                    if (!$friends->containsStrict('id', (int) $value)) {
                        $fail("The other user must be one of your friends.");
                    }
                },
                'exists:users,id',
            ],
        ]);
        $currUserId = Auth::id();
        $otherUserId = $request->input('otherUserId');
        $currUserName = Auth::user()->name;
        $otherUserName = User::find($request->input('otherUserId'))->name;
        return view('chat/private-chat', compact('currUserId', 'otherUserId', 'currUserName', 'otherUserName'));
    }

    public function sendMessage(Request $request) {
        $this->validate(request(), [
            'receiverId' => [
                'bail',
                new ReceiverIsFriend,
                'exists:users,id',
            ],
        ]);
        $sender = Auth::user();
        $receiverId = (int) $request->input('receiverId');
        $message = $request->input('message');
        broadcast(new MessageSent($sender->name, $sender->id, $receiverId, $message));
        return response()->json(['sender name' => $sender->name, 'message' => $message, 'receiver id' => $receiverId]);
    }
}
