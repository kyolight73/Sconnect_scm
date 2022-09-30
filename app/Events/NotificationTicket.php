<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NotificationTicket
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

private $marketerName;
private $tickeName;
private $workflow;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($marketerName,$tickeName,$workflow)
    {
        $this-> marketerName = $marketerName;
        $this-> tickeName = $tickeName;
        $this-> workflow = $workflow;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return ['send-message'];
    }
}

