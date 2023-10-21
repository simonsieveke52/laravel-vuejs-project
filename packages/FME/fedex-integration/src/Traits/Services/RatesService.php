<?php

namespace FME\Fedex\Traits\Services;

use App\Address;
use App\Product;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use FedEx\RateService\Request;
use Illuminate\Support\Collection;
use App\Repositories\CartRepository;
use Illuminate\Support\Facades\Cache;
use FedEx\RateService\ComplexType\Weight;
use FedEx\RateService\ComplexType\Payment;
use FedEx\RateService\SimpleType\PaymentType;
use FedEx\RateService\SimpleType\WeightUnits;
use FedEx\RateService\ComplexType\RateRequest;
use FedEx\RateService\SimpleType\PackagingType;
use FedEx\RateService\SimpleType\RateRequestType;
use FedEx\RateService\ComplexType\RequestedShipment;

trait RatesService
{
    /**
     * @return Request
     */
    public function getRatesService(): Request
    {
        $request = new Request();

        $request->getSoapClient()->__setLocation(
            config('fedex.sandbox') ? Request::TESTING_URL : Request::PRODUCTION_URL
        );

        return $request;
    }

    /**
     * @return Collection
     */
    protected function freeShipping()
    {
        $response = [];
        $key = 'FEDEX_GROUND';

        $response[$key]['label'] = 'Free Ground';
        $response[$key]['slug'] = $key;
        $response[$key]['serviceCode'] = 92;
        $response[$key]['cost'] = 0;
        $response[$key]['warning'] = null;
        $response[$key]['shipping_label'] = '';

        return collect([(Object) $response[$key]]);
    }

    /**
     * @param  Address     $address
     * @param  Collection  $products
     * @param  string|null $serviceCode
     * @return array
     */
    public function getRates(Address $address, Collection $products, string $serviceCode = null)
    {
        if ((new CartRepository())->getSubTotal() >= setting('free_shipping.threshold', 35)) {
            return $this->freeShipping();
        }

        if (! config('fedex.cache')) {
            return $this->getRatesRequest($address, $products, $serviceCode);
        }

        $self = $this;
        $cacheKey = md5($address->toJson . $products->toJson() . $serviceCode);

        return Cache::remember($cacheKey, now()->addDays(30), function () use ($self, $address, $products, $serviceCode) {
            return $this->getRatesRequest($address, $products, $serviceCode);
        });
    }

    /**
     * @param  Address     $address
     * @param  Collection  $products
     * @param  string|null $serviceCode
     *
     * @throws \Exception
     *
     * @return array
     */
    private function getRatesRequest(Address $address, Collection $products, string $serviceCode = null)
    {
        $rateRequest = $this->initRequest(new RateRequest());
        $rateRequest->Version->ServiceId = 'crs';
        $rateRequest->Version->Major = 24;
        $rateRequest->Version->Minor = 0;
        $rateRequest->Version->Intermediate = 0;

        $packages = $this->getPackages($products);

        $requestedShipment = (new RequestedShipment())
            ->setPackagingType(PackagingType::_YOUR_PACKAGING)
            ->setPreferredCurrency('USD')
            ->setShipper($this->getShipper())
            ->setRecipient($this->getShipTo($address))
            ->setShippingChargesPayment(
                (new Payment())->setPaymentType(PaymentType::_SENDER)
            )
            ->setRateRequestTypes([
                RateRequestType::_PREFERRED,
                RateRequestType::_LIST
            ])
            ->setRequestedPackageLineItems($packages)
            ->setPackageCount(count($packages));

        $rateRequest->setRequestedShipment($requestedShipment);

        $ratesResponse = $this->getRatesService()->getGetRatesReply($rateRequest, true);

        if (! isset($ratesResponse->RateReplyDetails) && isset($ratesResponse->Notifications)) {
            throw new \Exception($ratesResponse->Notifications->Message);
        }

        $response = [];
        $enabledServices = array_column(config('fedex.services'), 'key');

        foreach ($ratesResponse->RateReplyDetails as $rates) {
            if (count($rates->RatedShipmentDetails) === 0) {
                continue;
            }

            $key = $rates->ServiceType;

            if (! in_array($key, $enabledServices)) {
                continue;
            }

            $service = $rates->ServiceDescription;

            if (config('app.env') === 'local') {
                $response[$key]['response'] = $rates;
            }

            $cost = collect($rates->RatedShipmentDetails)->pluck('ShipmentRateDetail')->firstWhere('RateType', 'PAYOR_ACCOUNT_PACKAGE');

            if (! is_object($cost)) {
                continue;
            }

            $response[$key]['label'] = collect(config('fedex.services'))->firstWhere('key', $key)['label'];
            $response[$key]['slug'] = $key;
            $response[$key]['serviceCode'] = $service->Code;
            $response[$key]['cost'] = $cost->TotalNetCharge->Amount;
            $response[$key]['warning'] = null;
            $response[$key]['shipping_label'] = $service->Description;

            $response[$key] = (Object) $response[$key];
        }

        return $response;
    }
}
