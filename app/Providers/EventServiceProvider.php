<?php

namespace App\Providers;

use App\Events\NewQuizPublishedEvent;
use App\Events\NewReminderEvent;
use App\Events\PublishQuizResultsEvent;
use App\Listeners\NewQuizPublishedListener;
use App\Listeners\NewReminderListener;
use App\Listeners\PublishQuizResultsListener;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        NewReminderEvent::class => [
            NewReminderListener::class
        ],
        NewQuizPublishedEvent::class => [
            NewQuizPublishedListener::class
        ],
        PublishQuizResultsEvent::class => [
            PublishQuizResultsListener::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
