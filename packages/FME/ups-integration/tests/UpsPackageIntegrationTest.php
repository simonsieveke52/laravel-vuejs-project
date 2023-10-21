<?php

namespace Tests\Feature;

use App\Order;
use App\State;
use App\Address;
use Tests\TestCase;
use FME\Ups\UpsFacade;
use App\TrackingNumber;
use FME\Ups\UpsRepository;
use FME\Ups\UpsServiceProvider;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use FME\Ups\Http\Controllers\AddressValidationController;

class UpsPackageIntegrationTest extends TestCase
{
    /**
     * @return void
     */
    public function it_can_get_config()
    {
        $upsConfig = config('ups');

        $this->assertTrue(is_array($upsConfig) && ! empty($upsConfig));
    }

    /**
     * @return void
     */
    public function it_can_initialize_classes()
    {
        $ups = new UpsRepository();

        $this->assertTrue($ups instanceof UpsRepository);
    }

    /**
     * @return void
     */
    public function it_can_validate_address()
    {
        $address = Address::first();
        $address->address_1 = '1721 S ELLIOTT ST';
        $address->city = 'NewYork';
        $address->state_id = State::where('abv', 'NY')->first()->id;
        $address->zipcode = '74361';

        $response = UpsFacade::validateAddress($address);

        $this->assertTrue(is_object($response));
    }

    /**
     * @return void
     */
    public function it_can_get_rates()
    {
        $order = Order::confirmed()->orderBy('id', 'desc')->first();

        $rates = UpsFacade::getRates($order->validatedAddress, $order->products);

        $this->assertTrue(is_array($rates) || is_object($rates));
    }

    /**
     * @test
     * @return void
     */
    public function it_can_register_shipments_for_single_package()
    {
        $order = Order::whereHas('products')->orderBy('id', 'desc')->first();

        $order->markAsConfirmed();

        $shipping = UpsFacade::setAccountNumber(config('ups.accountNumber'))
            ->getShipping($order, collect([$order->products->first()]));

        $disk = 'tracking';

        $tracking = UpsFacade::storeTrackingNumber($order, $shipping, $disk);

        $this->assertTrue($tracking instanceof Collection);

        $this->assertTrue($tracking->count() === 1);

        $this->assertTrue($tracking->first()->exists);

        $this->assertTrue(
            Storage::disk($disk)->exists($tracking->file_name)
        );
    }

    /**
     * @test
     * @return void
     */
    public function it_can_register_shipments_for_multi_packages()
    {
        $order = Order::whereHas('products')->orderBy('id', 'desc')->first();

        $order->markAsConfirmed();

        $shipping = UpsFacade::setAccountNumber(config('ups.accountNumber'))
            ->getShipping($order, $order->products);

        $disk = 'tracking';

        $trackingNumbers = UpsFacade::storeTrackingNumber($order, $shipping, $disk);

        $this->assertTrue($trackingNumbers instanceof Collection);
        $this->assertTrue($trackingNumbers->count() === $order->products->sum('pivot.quantity'));

        foreach ($trackingNumbers as $tracking) {
            $this->assertTrue($tracking->exists);
            $this->assertTrue(
                Storage::disk($disk)->exists($tracking->file_name)
            );
        }
    }
}
