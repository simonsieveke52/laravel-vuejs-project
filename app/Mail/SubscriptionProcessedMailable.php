<?php

namespace App\Mail;

use App\OrderProduct;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SubscriptionProcessedMailable extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var Order
     */
    protected $order;

    /**
     * @var OrderProduct
     */
    protected $orderProduct;

    /**
     * Create a new message instance.
     */
    public function __construct(OrderProduct $orderProduct)
    {
        $this->order = $orderProduct->order;
        $this->orderProduct = $orderProduct;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = [
            'order' => $this->order,
            'products' => $this->orderProduct->product()->get(),
            'customer' => $this->order->customer,
            'billing_address' => $this->order->billing_address,
            'shipping_address' => $this->order->shipping_address,
            'status' => $this->order->orderStatus,
            'payment' => $this->order->payment_method,
            'discount' => $this->order->discount
        ];

        return $this
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->markdown('emails.orders.created', $data);
    }
}
