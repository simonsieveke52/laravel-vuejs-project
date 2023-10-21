<?php

namespace App\Jobs;

use App\Order;
use App\Mail\OrderMailable;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use App\Notifications\TextNotification;
use App\Notifications\OrderNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Repositories\Locate\LocateApiClient;
use Illuminate\Support\Facades\Notification;
use App\Repositories\Locate\LocateRepository;

class SendOrderNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Order
     */
    protected $order;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $this->order->notify(new OrderNotification($this->order));
            $this->order->markAsMailed();
        } catch (\Exception $exception) {
            logger($exception->getMessage());
        }
    }
}
