<?php

namespace App\Jobs;

use App\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Repositories\Payment\PaypalRepository;
use App\Repositories\Payment\AuthorizeNetRepository;

class CreatePaymentSubscriptionJob implements ShouldQueue
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
        $order = $this->order;

        $paymentRepository = new AuthorizeNetRepository();

        $this->order->subscriptions->each(function ($product) use ($paymentRepository, $order) {
            try {
                $subscriptionId = $paymentRepository->createSubscription(
                    $order,
                    "{$order->id}-{$product->id}",
                    $product->pivot->total,
                    (int) setting('subscription.frequency', 30)
                );

                $order->apiResponses()->create([
                    'caller' => 'createSubscription',
                    'content' => $subscriptionId,
                    'product_id' => $product->id,
                    'status' => true,
                ]);

                $product->pivot->subscription_id = $subscriptionId;
                $product->pivot->is_active_subscription = true;
                $product->pivot->save();
            } catch (\Exception $exception) {
                $order->apiResponses()->create([
                    'caller' => 'createSubscription',
                    'content' => $exception->getMessage(),
                    'product_id' => $product->id,
                    'status' => false,
                ]);

                throw $exception;
            }
        });
    }
}
