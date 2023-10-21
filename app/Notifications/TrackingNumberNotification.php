<?php

namespace App\Notifications;

use App\Order;
use Illuminate\Bus\Queueable;
use App\Mail\TrackingNumberMailable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class TrackingNumberNotification extends Notification
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
        if ($notifiable instanceof Order && $notifiable->exists) {
            return ['mail'];
        }

        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed  $notifiable
     *
     * @return TrackingNumberMailable
     */
    public function toMail($notifiable): TrackingNumberMailable
    {
        $order = $notifiable instanceof Order && $notifiable->exists
            ? $notifiable
            : $this->order;

        return (new TrackingNumberMailable($order))
            ->bcc(config('mail.bcc'))
            ->to($order->email);
    }
}
