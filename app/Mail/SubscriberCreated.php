<?php

namespace App\Mail;

use App\Subscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SubscriberCreated extends Mailable
{
    use Queueable, SerializesModels;

    /**
     *
     * @var Subscriber
     */
    private $subscriber;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Subscriber $subscriber)
    {
        $this->subscriber = $subscriber;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->to('carlos@fountainheadme.com')
            // ->to('info@naturalhouse.com')
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->markdown('emails.subscriber.created', [
                'subscriber' => $this->subscriber
            ]);
    }
}
