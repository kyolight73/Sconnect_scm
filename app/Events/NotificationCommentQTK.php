<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NotificationCommentQTK
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $title;
//
    public $message;
    public $ticket;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($txt_comment, $explode_id, $ticket)
    {
        $this->title = $txt_comment;
        $this->message = $explode_id;
        $this->ticket = $ticket;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return ['channel_'.$this->ticket];
    }
}

