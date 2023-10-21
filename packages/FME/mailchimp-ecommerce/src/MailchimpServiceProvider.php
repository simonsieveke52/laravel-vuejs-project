<?php

namespace FME\Mailchimp;

use FME\Mailchimp\Mailchimp;
use FME\Mailchimp\MailchimpClient;
use Illuminate\Support\ServiceProvider;

class MailchimpServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/mailchimp.php' => config_path('mailchimp.php'),
            ], 'config');
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/mailchimp.php', 'mailchimp');

        $this->app->singleton('Mailchimp', function () {
            return new Mailchimp(config('mailchimp.api_key'));
        });
    }
}
