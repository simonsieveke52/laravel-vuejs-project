<?php

namespace FME\Fedex\Traits;

use App\State;
use App\Address;
use App\Zipcode;

trait ValidatedAddressTrait
{

    /**
     * @param  array  $data
     * @return Address
     */
    public function createValidatedAddress(array $data): Address
    {
        $address = isset($data['EffectiveAddress']) ? $data['EffectiveAddress'] : $data;

        $zipcode = explode('-', $address['PostalCode']);
        $address['postcodePrimaryLow'] = $zipcode[0];
        $address['postcodeExtendedLow'] = isset($zipcode[1]) ? '-' . $zipcode[1] : '';
        $address['politicalDivision2'] = $address['City'] ?? '';

        try {
            $state = State::whereAbv($address['StateOrProvinceCode'])->firstOrFail();
            $stateId = $state->id;
        } catch (Exception $e) {
            try {
                $zipcode = Zipcode::whereName($address['postcodePrimaryLow'])->firstOrFail();
                $stateId = $zipcode->state_id;
            } catch (\Exception $e) {
            }
        }

        $cityName = $address['politicalDivision2'] ?? null;

        try {
            if ($cityName === '') {
                throw new \Exception("Error Processing Request");
            }
        } catch (\Exception $e) {
            try {
                $zipcode = Zipcode::whereName($address['postcodePrimaryLow'])->firstOrFail();
                $city = $zipcode->city;
                if (! $city instanceof City) {
                    throw new \Exception("Error Processing Request");
                }
                $cityName = $city->name;
            } catch (\Exception $e) {
            }
        }

        return Address::create([
            'country_id' => 1,
            'address_1' => $address['StreetLines'],
            'validated_response' => json_encode($data),
            'zipcode' => str_replace('--', '-', $address['postcodePrimaryLow'] . $address['postcodeExtendedLow']),
            'state_id' => $stateId ?? null,
            'city' => $cityName ?? null,
            'type' => 'validated',
        ]);
    }
}
