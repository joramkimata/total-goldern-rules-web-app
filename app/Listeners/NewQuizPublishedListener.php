<?php

namespace App\Listeners;

use App\Events\NewQuizPublishedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewQuizPublishedListener
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
     * @param  NewQuizPublishedEvent  $event
     * @return void
     */
    public function handle(NewQuizPublishedEvent $event)
    {
        //
        \App\HelperX::sendEmails('emails.quizpublished_mail', 'NEW QUIZ PUBLISHED!');
    }
}
