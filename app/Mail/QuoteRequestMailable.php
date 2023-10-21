<?php

namespace App\Mail;

use App\Quote;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class QuoteRequestMailable extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var Quote
     */
    protected $quote;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Quote $quote)
    {
        $this->quote = $quote;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->bcc(config('mail.bcc'))
            ->subject('New Quote Request From ' . ucfirst(siteBaseDomain()))
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->markdown('emails.notifications.quote-request', [
                'quote' => $this->quote
            ]);
    }
}
