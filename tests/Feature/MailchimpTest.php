<?php

namespace Tests\Feature;

use App\Product;
use Tests\TestCase;
use FME\Mailchimp\Mailchimp;
use MailchimpAPI\Responses\SuccessResponse;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MailchimpTest extends TestCase
{
    use WithFaker;

    /**
     * @return void
     */
    public function test_can_create_mailchimp_classes()
    {
        $mailchimp = new Mailchimp(config('mailchimp.api_key'));

        $this->assertTrue($mailchimp->getClient() instanceof \MailchimpAPI\Mailchimp);
    }

    /**
     * @return void
     */
    public function test_can_create_ecommerce_store()
    {
        $data = [
            "list_id" => '9e3c0f59d4',
            "name" => "Fountainheadme TEST Store",
            "domain" => "https://startershop.fountainheadme.com/",
            "email_address" => "ryan@fountainheadme.com",
            "currency_code" => "USD"
        ];

        $response = (new Mailchimp(config('mailchimp.api_key')))->createStore($data);

        $this->assertTrue($response instanceof SuccessResponse);
    }

    /**
     * @return void
     */
    public function test_can_create_ecommerce_customer()
    {
        $data = [
            'email_address' => $this->faker->freeEmail,
            'opt_in_status' => mt_rand(0, 1) === 0,
            'company' => $this->faker->company,
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'total_spent' => mt_rand(5, 100)
        ];

        $mailchimp = (new Mailchimp(config('mailchimp.api_key')))
            ->setStoreId('store_J6_1595790689');

        $response = $mailchimp->createCustomer($data);

        // customer_id: customer_8P_1595792192

        $this->assertTrue($response instanceof SuccessResponse);
    }

    public function test_add_products()
    {
        $products = Product::find([13, 14, 15, 16]);

        $mailchimp = (new Mailchimp(config('mailchimp.api_key')))
            ->setStoreId('store_J6_1595790689');

        $response = $mailchimp->addProducts($products);

        $this->assertTrue(is_array($response));

        $this->assertTrue(! collect($response)->where('status', true)->isEmpty());
    }

    /**
     * @return void
     */
    public function test_can_create_ecommerce_cart()
    {
        $customerData = [
            'email_address' => $this->faker->freeEmail,
            'opt_in_status' => true,
            'company' => $this->faker->company,
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'total_spent' => mt_rand(5, 100)
        ];

        $cartData = [
            'checkout_url' => 'https://startershop.fountainheadme.com/guest-checkout',
            'order_total' => mt_rand(1, 10),
            'tax_total' => mt_rand(1, 10),
        ];

        $mailchimp = (new Mailchimp(config('mailchimp.api_key')))
            ->setStoreId('store_J6_1595790689');

        $products = Product::find([13, 14, 15, 16]);

        $response = $mailchimp->createCart($products, $cartData, $customerData);

        $this->assertTrue($response instanceof SuccessResponse);
    }
}
