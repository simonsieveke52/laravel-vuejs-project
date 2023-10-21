<?php

namespace App\Notifications;

use App\OrderProduct;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\URL;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SubscriptionProcessedNotification extends Notification
{
    use Queueable;

    /**
     * @var Order
     */
    protected $order;

    /**
     * @var OrderProduct
     */
    protected $orderProduct;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(OrderProduct $orderProduct)
    {
        $this->orderProduct = $orderProduct;
        $this->order = $orderProduct->order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $data = [
            'order' => $this->order,
            'customer' => $this->order->customer,
            'billing_address' => $this->order->billing_address,
            'shipping_address' => $this->order->shipping_address,
            'status' => $this->order->orderStatus,
            'payment' => $this->order->payment_method,
            'discount' => $this->order->discount,
            'products' => $this->order->products->where('id', $this->orderProduct->product_id)->where('pivot.is_subscription', 1)->values(),
            'showCancelSubscribe' => true,
            'cancelSubscriptionUrl' => URL::temporarySignedRoute('subscription.destroy', now()->addDays(7), ['orderProduct' => $this->orderProduct]),
        ];

        return (new MailMessage)
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->markdown('emails.orders.created', $data);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
