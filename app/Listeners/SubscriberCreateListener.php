<?php

namespace App\Listeners;

use App\Mail\SubscriberCreated;
use Illuminate\Support\Facades\Mail;
use App\Events\SubscriberCreateEvent;

class SubscriberCreateListener
{
    /**
     * Handle the event.
     *
     * @param  SubscriberCreateEvent  $event
     * @return void
     */
    public function handle(SubscriberCreateEvent $event)
    {
        Mail::to(config('mail.bcc'))
            ->send(new SubscriberCreated($event->subscribe));
    }
}
