<?php

namespace FME\Mailchimp;

use Illuminate\Support\Str;
use FME\Mailchimp\Traits\Ecommerce;
use FME\Mailchimp\Traits\Validator;
use MailchimpAPI\Responses\MailchimpResponse;
use MailchimpAPI\Mailchimp as MailchimpClient;

class Mailchimp
{
    use Validator, Ecommerce;

    /**
     * @var \MailchimpAPI\Mailchimp
     */
    protected $client;

    /**
     * @var string
     */
    protected $storeId;

    /**
     * @param string $apiKey
     */
    public function __construct(string $apiKey)
    {
        $this->client = new MailchimpClient($apiKey);
    }

    /**
     * @return \MailchimpAPI\Mailchimp
     */
    public function getClient(): \MailchimpAPI\Mailchimp
    {
        return $this->client;
    }
    
    /**
     * @return null|string
     */
    public function getStoreId()
    {
        return isset($this->storeId) ? (string) $this->storeId : null;
    }

    /**
     * @param null|string
     * @return self
     */
    public function setStoreId(string $storeId = null)
    {
        $this->storeId = $storeId;

        return $this;
    }
}
