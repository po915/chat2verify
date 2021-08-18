<?php

namespace App\Events;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Class IncomingMessage
 * @package App\Events
 */
class IncomingMessage implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Conversation
     */
    protected $conversation;

    /**
     * @var Message
     */
    protected $message;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct( Conversation $conversation, Message  $message)
    {
        $this->conversation = $conversation;
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('App.Models.Team.' . $this->conversation->team->id);
    }

    /**
     * @return array
     */
    public function broadcastWith() {
        return [ 'conversation' => $this->conversation->id, 'message' => $this->message->toArray() ];
    }

    /**
     * @return string
     */
    public function broadcastAs() {
        return 'incoming-message';
    }
}
