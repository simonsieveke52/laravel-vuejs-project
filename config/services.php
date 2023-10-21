<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */
   
    'mandrill' => [
        'secret' => env('MANDRILL_SECRET'),
    ],

    'slack' => [
        'order_notification_webhook' => 'https://hooks.slack.com/services/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'
    ]
];
