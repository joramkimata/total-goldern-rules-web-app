<?php

namespace App\Listeners;

use App\Events\PublishQuizResultsEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class PublishQuizResultsListener implements ShouldQueue
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
     * @param  PublishQuizResultsEvent  $event
     * @return void
     */
    public function handle(PublishQuizResultsEvent $event)
    {
        //
        // Send Email
        \App\HelperX::sendEmails('emails.quizresults_mail', 'QUIZ RESULTS ARE OUT!');
    }
}
