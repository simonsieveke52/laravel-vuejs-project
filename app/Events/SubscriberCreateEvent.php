<?php

namespace App\Events;

use App\Subscriber;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SubscriberCreateEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Subscriber
     */
    public $subscribe;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Subscriber $subscribe)
    {
        $this->subscribe = $subscribe;
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
