<?php

namespace Tests\Feature;

use App\Order;
use App\Address;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use FME\Ups\Http\Controllers\AddressValidationController;

class ShipStationPackageIntegrationTest extends TestCase
{
    /**
     * @test
     * @return void
     */
    public function it_can_get_config()
    {
        $config = config('shipstation');

        dd($config);

        $this->assertTrue(is_array($config) && ! empty($config));
    }

    /**
     * @test
     * @return void
     */
    public function it_can_initialize_classes()
    {
        $ups = new UpsRepository();

        $this->assertTrue($ups instanceof UpsRepository);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_validate_address()
    {
        $address = Address::first();

        $response = UpsFacade::validateAddress($address);

        $this->assertTrue(is_object($response));
    }

    /**
     * @test
     * @return void
     */
    public function it_can_get_rates()
    {
        $order = Order::confirmed()->orderBy('id', 'desc')->first();

        $rates = UpsFacade::getRates($order->validatedAddress, $order->products);

        $this->assertTrue(is_array($rates) || is_object($rates));
    }
}
