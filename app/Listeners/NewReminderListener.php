<?php

namespace App\Listeners;

use App\Events\NewReminderEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewReminderListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param NewReminderEvent $event
     * @return void
     */
    public function handle(NewReminderEvent $event)
    {
        // Send Email
        \App\HelperX::sendReminders($event->view, $event->subject, $event->bod, $event->recipients);
    }
}
