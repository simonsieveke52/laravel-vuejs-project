<?php

namespace App\Notifications;

use App\Order;
use App\Mail\OrderMailable;
use Illuminate\Bus\Queueable;
use TCG\Voyager\Facades\Voyager;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;

class OrderNotification extends Notification
{
    use Queueable;

    /**
     * @var Order
     */
    protected $order;
    
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }


    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        switch ($notifiable) {
            default:
                return ['mail'];
        }
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed  $notifiable
     *
     * @return OrderMailable
     */
    public function toMail($notifiable): OrderMailable
    {
        $order = $notifiable instanceof Order && $notifiable->id === $this->order->id
            ? $notifiable
            : $this->order;

        $bcc = setting('emails.order_bcc', config('mail.bcc'));

        if (! is_array($bcc) && is_string($bcc)) {
            $bcc = array_map('trim', explode(',', $bcc));
        }

        if (is_null($bcc)) {
            $bcc = config('mail.bcc');
        }

        return (new OrderMailable($order))
            ->to($order->email)
            ->bcc($bcc)
            ->subject("Your order from ".ucfirst(ucfirst(siteBaseDomain()))." - Order #{$order->id}");
    }

    /**
     * Get the Slack representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return SlackMessage
     */
    public function toSlack($notifiable)
    {
        $order = $notifiable instanceof Order && $notifiable->id === $this->order->id
            ? $notifiable
            : $this->order;

        $total = '$' . number_format($order->total, 2);
        return (new SlackMessage())
            ->to('#project-chennel-here')
            ->content("Order Completed: #{$order->id} by {$order->name} for {$total} - {$order->order_source}");
    }
}
