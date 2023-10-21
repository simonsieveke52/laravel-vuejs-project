<?php

namespace App\Listeners;

use App\Order;
use App\Mail\OrderMailable;
use App\Events\OrderCreateEvent;
use App\Jobs\ReportOrderToApiJob;
use App\Jobs\SendOrderNotification;
use Illuminate\Support\Facades\Mail;
use App\Repositories\OrderRepository;
use App\Notifications\TextNotification;
use App\Notifications\OrderNotification;
use Illuminate\Queue\InteractsWithQueue;
use App\Jobs\CreatePaymentSubscriptionJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;
use App\Repositories\Locate\LocateRepository;
use App\Notifications\OrderReportedNotification;

class OrderCreateEventListener
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        try {
            SendOrderNotification::dispatch($event->order)->onQueue('default');
        } catch (\Exception $exception) {
            logger($exception->getMessage());
        }

        try {
            ReportOrderToApiJob::dispatch($event->order)->onQueue('default');
        } catch (\Exception $exception) {
            logger($exception->getMessage());
        }

        if ($event->order->subscriptions->isEmpty() || $event->order->payment_method === 'paypal') {
            return true;
        }

        try {
            CreatePaymentSubscriptionJob::dispatch($event->order)->onQueue('default');
        } catch (\Exception $exception) {
            logger($exception->getMessage());
        }
    }
}
