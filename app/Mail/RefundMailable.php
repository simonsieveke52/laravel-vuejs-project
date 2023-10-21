<?php

namespace App\Mail;

use App\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RefundMailable extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var Order
     */
    protected $order;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $order = $this->order;
        $name = config('mail.from.name');
        $address = config('mail.from.address');

        return $this->from($address, $name)
            ->to($this->order->email)
            ->bcc(config('mail.bcc'))
            ->replyTo(config('mail.replyTo'))
            ->subject('Order #'.$order->id.' from '. ucfirst(siteBaseDomain()) .' has been UPDATED.')
            ->view('emails.customer.refund', compact('order'));
    }
}
