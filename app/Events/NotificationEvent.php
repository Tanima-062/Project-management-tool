<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NotificationEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    private $notifier_channel;
    private $notifier_event;

    public function __construct($channel, $event, $message)
    {
        $this->message = $message;
        $this->notifier_channel = $channel;
        $this->notifier_event = $event;
    }

    public function broadcastOn()
    {
        return [$this->notifier_channel];
    }

    public function broadcastAs()
    {
        return $this->notifier_event;
    }
}
