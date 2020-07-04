<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

class UserActionsInProducts
{
    use Dispatchable, InteractsWithSockets;

    public $actionMessage = "";

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($actionMessage)
    {
        //dd($actionMessage);
        $this->actionMessage = $actionMessage;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
