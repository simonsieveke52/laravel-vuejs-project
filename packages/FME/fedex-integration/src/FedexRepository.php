<?php

namespace FME\Fedex;

use App\Address;
use App\Product;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use FedEx\AbstractComplexType;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use FedEx\RateService\ComplexType\Party;
use FedEx\RateService\ComplexType\Weight;
use FME\Fedex\Traits\Services\RatesService;
use FedEx\RateService\SimpleType\WeightUnits;
use FME\Fedex\Traits\Services\AddressValidationService;
use FedEx\RateService\ComplexType\Address as FedexAddress;
use FedEx\RateService\ComplexType\RequestedPackageLineItem;

class FedexRepository
{
    use AddressValidationService, RatesService;

    /**
     * Max weight per package in lbs
     */
    public const MAX_PACKAGE_WEIGHT = 150;

    /**
     * @var array
     */
    protected $config;

    /**
     * Fedex Repository constructor
     */
    public function __construct()
    {
        $this->config = config('fedex');

        if (count($this->config) === 0) {
            throw new \Exception("Invalid Fedex config");
        }

        foreach (['key', 'password', 'account_number', 'meter_number', 'sandbox'] as $key) {
            if (isset($this->config[$key])) {
                continue;
            }

            throw new \Exception("{$key} is required for Fedex");
        }

        set_time_limit(0);
    }

    /**
     * @return AbstractComplexType
     */
    public function initRequest(AbstractComplexType $request): AbstractComplexType
    {
        $request->WebAuthenticationDetail->UserCredential->Key = trim($this->config['key']);
        $request->WebAuthenticationDetail->UserCredential->Password = trim($this->config['password']);
        $request->ClientDetail->AccountNumber = trim($this->config['account_number']);
        $request->ClientDetail->MeterNumber = trim($this->config['meter_number']);

        return $request;
    }

    /**
     * @return Party
     */
    public function getShipFrom(): Party
    {
        return $this->getShipper();
    }

    /**
     * @return Party
     */
    public function getShipper(): Party
    {
        $fedexAddress = (new FedexAddress())->setCity(config('fedex.shipFrom.city'))
            ->setStateOrProvinceCode(config('fedex.shipFrom.state'))
            ->setPostalCode(config('fedex.shipFrom.postalCode'))
            ->setCountryCode(config('fedex.shipFrom.country'))
            ->setStreetLines(
                strlen(config('fedex.shipFrom.street2')) > 0
                    ? [config('fedex.shipFrom.street1'), config('fedex.shipFrom.street2')]
                    : [config('fedex.shipFrom.street1')]
            );

        return (new Party())
            ->setAccountNumber(trim($this->config['account_number']))
            ->setAddress($fedexAddress);
    }

    /**
     * @return Party
     */
    public function getShipTo(Address $address): Party
    {
        $validated = json_decode($address->validated_response);

        if (! is_object($validated)) {
            throw new \Exception("Invalid fedex Address");
        }

        $fedexAddress = (new FedexAddress())->setCity($address->city)
            ->setStateOrProvinceCode($address->state->abv)
            ->setPostalCode($address->zipcode)
            ->setCountryCode($address->country->iso)
            ->setStreetLines(
                strlen($address->address_2) > 0
                    ? [$address->address_1, $address->address_2]
                    : [$address->address_1]
            );

        return (new Party())
            ->setAddress($fedexAddress);
    }

    /**
     * @return Party
     */
    public function getSoldTo(Address $address): Party
    {
        return $this->getShipTo($address);
    }

    /**
     * @param  Collection   $products
     * @param  bool|boolean $allowFreeShipping
     *
     * @return array
     */
    public function getPackages(Collection $products, bool $allowFreeShipping = false)
    {
        $totalWeight = 0;
        $packages = [];

        $products->each(function ($cartItem) use (&$totalWeight, &$packages, $allowFreeShipping) {
            $product = $cartItem instanceof Product ? $cartItem : $cartItem->product;

            if ($product->is_free_shipping && $allowFreeShipping) {
                return true;
            }

            $weight = floatval($product->weight) > 0
                ? $product->weight
                : 1;

            if (! $cartItem instanceof Product) {
                $weight *= $cartItem->quantity;
            } elseif (isset($product->pivot)) {
                $weight *= $product->pivot->quantity;
            }

            // add package
            if ($totalWeight > abs(self::MAX_PACKAGE_WEIGHT - $weight) && $totalWeight < self::MAX_PACKAGE_WEIGHT) {
                $package = new RequestedPackageLineItem();
                $package->setGroupPackageCount(1);
                $package->setWeight(
                    (new Weight())
                        ->setUnits(WeightUnits::_LB)
                        ->setValue($totalWeight)
                );

                $packages[] = $package;
                $totalWeight = 0;
            }

            $totalWeight += $weight;
        });

        // total packges to send
        $iterations = ceil($totalWeight/self::MAX_PACKAGE_WEIGHT);

        for ($i = 0; $i < $iterations; $i++) {
            $package = new RequestedPackageLineItem();
            $package->setGroupPackageCount(1);
            $weight = self::MAX_PACKAGE_WEIGHT;

            // last iteration
            if ($i == ($iterations - 1)) {
                $weight = $totalWeight - self::MAX_PACKAGE_WEIGHT * ($iterations -1);
            }

            if ($weight < 0) {
                continue;
            }
            
            $package->setWeight(
                (new Weight())
                    ->setUnits(WeightUnits::_LB)
                    ->setValue($weight)
            );

            $packages[] = $package;
        }

        return $packages;
    }

    /**
     * Set package weight
     *
     * @param &$package
     * @param float $product
     */
    public function setWeight(&$package, $weight)
    {
        // set package weight
        $weight = round($weight);
        
        // we can't send a package with 0lbs
        if ($weight <= 0) {
            $weight = 1;
        }

        $package->getPackageWeight()->setWeight($weight);
        $package->getPackageWeight()->setUnitOfMeasurement(
            ( new UnitOfMeasurement )->setCode(UnitOfMeasurement::UOM_LBS)
        );
    }

    /**
     * @param  string|null $serviceCode
     * @return array
     */
    public function getEnabledServices(string $serviceCode = null)
    {
        if ($serviceCode === null) {
            return config('ups.services');
        }

        $index = array_search($serviceCode, array_column(config('ups.services'), 'Code'));

        if ($index === false || ! isset(config('ups.services')[$index])) {
            throw new \Exception("Invalid service");
        }

        return [
            config('ups.services')[$index]
        ];
    }
}
