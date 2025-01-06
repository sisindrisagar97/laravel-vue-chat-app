<?php


namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function broadcastOn()
    {
        // \Log::info('Broadcasting sent msg', $this->message->to);
        return new PrivateChannel('chat.' . $this->message->to);
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->message->id,
            'from' => $this->message->from,
            'to' => $this->message->to,
            'text' => $this->message->text,
            'created_at' => $this->message->created_at->toDateTimeString(),
        ];
    }
}
