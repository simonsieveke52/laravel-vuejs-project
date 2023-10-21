<?php

namespace FME\Mailchimp;

use Illuminate\Support\Facades\Facade;

/**
 * @see \FME\Mailchimp\Mailchimp
 */
class MailchimpFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Mailchimp';
    }
}
