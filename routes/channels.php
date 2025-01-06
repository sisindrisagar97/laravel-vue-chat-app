<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('chat.{userId}', function ($user, $userId) {
    \Log::info('Authorizing private-chat channel', [
        'authenticated_user_id' => $user->id,
        'requested_user_id' => $userId,
        'match' => (int) $user->id === (int) $userId,
    ]);
    return (int) $user->id === (int) $userId;

});

