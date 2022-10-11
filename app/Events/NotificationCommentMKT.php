<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationCommentMKT
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
        return ['channelMkt_'.$this->ticket];
    }
}
