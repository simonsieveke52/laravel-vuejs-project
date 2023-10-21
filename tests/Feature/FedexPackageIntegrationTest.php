<?php

namespace Tests\Feature;

use App\Order;
use App\State;
use App\Address;
use Tests\TestCase;
use FME\Fedex\FedexFacade;
use FME\Fedex\FedexRepository;
use App\Repositories\AddressRepository;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use FedEx\AddressValidationService\ComplexType\AddressValidationRequest;

class FedexPackageIntegrationTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    // public function test_can_create_request()
    // {
    //     $repo = new FedexRepository();
    //     $request = $repo->initRequest(new AddressValidationRequest());
    //     $this->assertTrue($request instanceof AddressValidationRequest);
    // }

    // /**
    //  * @return void
    //  */
    // public function test_basic_repository_functions()
    // {
    //     $repo = new FedexRepository();

    //     $shipFrom = $repo->getShipFrom();
    //     $this->assertTrue(is_object($shipFrom));

    //     $shipper = $repo->getShipper();
    //     $this->assertTrue(is_object($shipper));

    //     $address = Address::validated()->first();

    //     $shipTo = $repo->getShipTo($address);
    //     $this->assertTrue(is_object($shipTo));

    //     $soldTo = $repo->getSoldTo($address);
    //     $this->assertTrue(is_object($soldTo));
    // }

    // /**
    //  * @return void
    //  */
    // public function test_it_can_validate_address()
    // {
    //     $address = Address::first();

    //     // invalid address
    //     $address->city = 'Santa Monica';
    //     $address->state_id = 11;
    //     $address->zipcode = 11111;
    //     $address->address_1 = 'Broadway';
        
    //     $response = FedexFacade::validateAddress($address);
    //     $this->assertFalse($response);

    //     // valid address
    //     $address->city = 'Santa Monica';
    //     $address->state_id = 11;
    //     $address->zipcode = 90401;
    //     $address->address_1 = '1100 Broadway';

    //     $response = FedexFacade::validateAddress($address);
    //     $this->assertTrue(is_object($response));

    //     $data['validatedAddress'] = json_decode(json_encode($response), true);

    //     $addressRepository = new AddressRepository();
    //     $address = $addressRepository->createValidatedAddress($data['validatedAddress']);
    //     $this->assertTrue($address instanceof Address && $address->exists);
    // }

    /**
     * @return void
     */
    public function test_it_can_get_rates()
    {
        $order = Order::confirmed()->orderBy('id', 'desc')->first();

        if (! $order->validatedAddress instanceof Address) {
            $order->addresses()->save(Address::validated()->first());
        }

        $rates = FedexFacade::getRates($order->validatedAddress, $order->products);

        $this->assertTrue(is_array($rates) || is_object($rates));
    }
}
