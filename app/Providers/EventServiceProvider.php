<?php

namespace App\Providers;

use App\Events\OrderCreateEvent;
use Illuminate\Auth\Events\Login;
use App\Events\SubscriberCreateEvent;
use Illuminate\Support\Facades\Event;
use App\Events\TrackingNumberCreatedEvent;
use App\Listeners\OrderCreateEventListener;
use App\Listeners\SubscriberCreateListener;
use App\Listeners\TrackingNumberCreatedListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        SubscriberCreateEvent::class => [
            SubscriberCreateListener::class,
        ],
        OrderCreateEvent::class => [
            OrderCreateEventListener::class,
        ],
        Login::class => [
            'App\Listeners\EmployeeListener@onUserLogin',
        ],
        TrackingNumberCreatedEvent::class => [
            TrackingNumberCreatedListener::class,
        ]
    ];
}
