<?php

namespace FME\Mailchimp\Traits;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use App\Repositories\CartRepository;

trait Validator
{

    /**
     * @param  array $requiredKeys
     * @param  array $array
     * @return bool
     * @throws \Exception
     */
    public function validateArrayKeys($requiredKeys, $array)
    {
        $arrayDiff = array_diff($requiredKeys, array_keys($array));

        if (count($arrayDiff) > 0) {
            throw new \Exception("Could not process request, missing required keys (".implode(', ', $arrayDiff).")");
        }

        return true;
    }

    /**
     * @param  array $customerData
     * @return array
     */
    public function getValidatedCustomerData(array $customerData): array
    {
        $requiredKeys = [
            'email_address', 'opt_in_status', 'company', 'first_name', 'last_name', 'total_spent'
        ];
        
        $this->validateArrayKeys($requiredKeys, $customerData);

        if (! in_array('id', array_keys($customerData))) {
            $customerData['id'] = 'customer_' . Str::random(2) . '_' . time();
        }

        return $customerData;
    }

    /**
     * @param  array $customerData
     * @param  Collection $products
     * @return array
     */
    public function getValidatedCartData(array $cartData, Collection $products): array
    {
        $requiredKeys = [
            'checkout_url', 'order_total', 'tax_total'
        ];
        
        $this->validateArrayKeys($requiredKeys, $cartData);

        if (! in_array('id', array_keys($cartData))) {
            $cartData['id'] = 'cart_' . Str::random(4) . '_' . time();
        }

        if (! in_array('customer', array_keys($cartData))) {
            $cartData['customer'] = [];
        }

        if (! in_array('currency_code', array_keys($cartData))) {
            $cartData['currency_code'] = 'USD';
        }

        if (! in_array('lines', array_keys($cartData))) {
            $cartData['lines'] = $products->map(function ($product) use ($cartData) {
                return [
                        'id' => $cartData['id'] . '-' . $product->id,
                        'product_id' => (string) $product->id,
                        'product_variant_id' => (string) $product->id,
                        'quantity' => $product->quantity,
                        'price' => $product->price,
                    ];
            })
                ->values()
                ->toArray();
        }

        return $cartData;
    }
}
