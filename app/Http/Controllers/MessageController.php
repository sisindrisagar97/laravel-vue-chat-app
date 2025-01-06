<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use App\Events\MessageSent;

class MessageController extends Controller
{
    public function getMessages($user)
    {
       
        $loggedInUserId = auth()->id(); 
        $messages = Message::where(function ($query) use ($loggedInUserId, $user) {
            $query->where('from', $loggedInUserId)->where('to', $user);
        })
        ->orWhere(function ($query) use ($loggedInUserId, $user) {
            $query->where('from', $user)->where('to', $loggedInUserId);
        })
        ->orderBy('created_at', 'asc')
        ->get();

        return response()->json($messages);
    }

    public function store(Request $request)
    {
        
        $validated = $request->validate([
            'from' => 'required|exists:users,id',
            'to' => 'required|exists:users,id',
            'text' => 'required|string',
        ]);
        
        try {
            $message = Message::create($validated);
            // MessageSent::dispatch($message);
            broadcast(new MessageSent($message))->toOthers();
    
            return response()->json([
                'status' => 'success',
                'message' => $message,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
