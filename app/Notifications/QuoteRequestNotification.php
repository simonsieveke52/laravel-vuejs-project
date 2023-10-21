<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use App\Mail\QuoteRequestMailable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class QuoteRequestNotification extends Notification
{
    use Queueable;

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
     * @return QuoteRequestMailable
     */
    public function toMail($notifiable): QuoteRequestMailable
    {
        return new QuoteRequestMailable($notifiable);
    }
}
