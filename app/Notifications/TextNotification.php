<?php

namespace App\Notifications;

use App\UserFile;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;

class TextNotification extends Notification
{
    use Queueable;

    /**
     * @var string
     */
    protected $text;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(string $text)
    {
        $this->text = $text;
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
     * Get the Slack representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return SlackMessage
     */
    public function toSlack($notifiable)
    {
        switch ($notifiable) {
            default:
                return (new SlackMessage())
                    ->linkNames()
                    ->to('#project-channel-here')
                    ->content($this->text);
        }
    }
}
