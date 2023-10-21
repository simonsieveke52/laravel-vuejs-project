<?php

namespace FME\Fedex\Traits\Services;

use App\Address;
use App\Product;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use FedEx\AddressValidationService\Request;
use FedEx\AddressValidationService\ComplexType\AddressToValidate;
use FedEx\AddressValidationService\ComplexType\Address as FedexAddress;
use FedEx\AddressValidationService\ComplexType\AddressValidationRequest;

trait AddressValidationService
{
    /**
     * @return Request
     */
    public function getAddressValidatorService(): Request
    {
        $request = new Request();

        $request->getSoapClient()->__setLocation(
            config('fedex.sandbox') ? Request::TESTING_URL : Request::PRODUCTION_URL
        );

        return $request;
    }

    /**
     * Address Validation
     *
     * @return mixed
     */
    public function validateAddress(Address $address)
    {
        if (! config('fedex.cache') || true) {
            return json_decode(json_encode($this->validateAddressRequest($address)));
        }

        $self = $this;
        $cacheKey = md5($address->toJson());

        return Cache::remember($cacheKey, now()->addDays(30), function () use ($self, $address) {
            $response = $self->validateAddressRequest($address);
            return json_decode(json_encode($response));
        });
    }

    /**
     * @param  Address $address
     * @return response
     */
    protected function validateAddressRequest(Address $address)
    {
        $addressValidationRequest = $this->initRequest(new AddressValidationRequest());
        $addressValidationRequest->Version->ServiceId    = 'aval';
        $addressValidationRequest->Version->Major = 4;
        $addressValidationRequest->Version->Intermediate = 0;
        $addressValidationRequest->Version->Minor = 0;

        $fedexAddress = (new FedexAddress())->setCity($address->city)
            ->setStateOrProvinceCode($address->state->abv)
            ->setPostalCode($address->zipcode)
            ->setCountryCode($address->country->iso)
            ->setStreetLines(
                strlen($address->address_2) > 0
                    ? [$address->address_1, $address->address_2]
                    : [$address->address_1]
            );

        $addressValidationRequest->setAddressesToValidate([
            (new AddressToValidate())->setAddress($fedexAddress)
        ]);

        $response = $this->getAddressValidatorService()->getAddressValidationReply($addressValidationRequest);

        if ($response->HighestSeverity === 'ERROR' || count($response->AddressResults) === 0) {
            return false;
        }

        $validatedResult = $response->AddressResults[0];

        $response = $validatedResult->toArray();
        $attributes = collect(collect($response)->get('Attributes'))->pluck('Value', 'Name')->toArray();

        if (
            ($validatedResult->State !== 'STANDARDIZED') ||
            ($attributes['Resolved'] === 'false' && $attributes['DPV'] === 'false')
        ) {
            return false;
        }

        return (Object) $validatedResult->toArray();
    }
}
