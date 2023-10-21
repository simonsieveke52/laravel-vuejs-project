<?php

namespace App\Notifications;

use App\ContactRequest;
use Illuminate\Bus\Queueable;
use App\Mail\ContactRequestMailable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ContactRequestNotification extends Notification
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
     * @return ContactRequestMailable
     */
    public function toMail($notifiable): ContactRequestMailable
    {
        return new ContactRequestMailable($notifiable);
    }
}
