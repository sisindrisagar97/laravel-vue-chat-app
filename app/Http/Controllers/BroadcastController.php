<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class BroadcastController extends Controller
{
    public function authenticate(Request $request)
    {
      
        if (Auth::check()) {
            return Broadcast::channel('chat.' . $request->userId, function ($user) use ($request) {
                return (int) $user->id === (int) $request->userId;
            });
        }

        return response('Unauthorized', 403);
    }
}

