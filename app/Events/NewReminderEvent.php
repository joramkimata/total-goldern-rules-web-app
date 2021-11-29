<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NewReminderEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $view;
    public $subject;
    public $bod;
    public $recipients;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($view, $subject, $bod, $recipients)
    {
        //
        $this->view = $view;
        $this->subject = $subject;
        $this->bod = $bod;
        $this->recipients = $recipients;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
