<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;

class MessageController extends Controller
{
    public function index(Request $request, User $user)
    {
        $authId = auth()->id();
        $messages = Message::where(function($q) use ($authId, $user) {
            $q->where('from_user_id', $authId)->where('to_user_id', $user->id);
        })->orWhere(function($q) use ($authId, $user) {
            $q->where('from_user_id', $user->id)->where('to_user_id', $authId);
        })->orderBy('created_at')->get();
        return response()->json($messages);
    }

    public function store(Request $request)
    {
        $request->validate([
            'to_user_id' => 'required|exists:users,id',
            'content' => 'required|string'
        ]);
        $message = Message::create([
            'from_user_id' => auth()->id(),
            'to_user_id' => $request->to_user_id,
            'content' => $request->content
        ]);
        return response()->json($message);
    }
}
