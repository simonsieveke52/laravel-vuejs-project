<?php

namespace App\Mail;

use App\ContactRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContactRequestMailable extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var ContactRequest
     */
    protected $contact;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(ContactRequest $contact)
    {
        $this->contact = $contact;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->bcc(config('mail.bcc'))
            ->subject('New Contact Request From ' . ucfirst(siteBaseDomain()))
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->markdown('emails.notifications.contact-request', [
                'contact' => $this->contact
            ]);
    }
}
