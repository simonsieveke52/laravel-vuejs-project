<?php

namespace FME\Mailchimp\Traits;

use Illuminate\Support\Str;
use MailchimpAPI\Resources\EcommerceStores;
use MailchimpAPI\Responses\FailureResponse;
use MailchimpAPI\Responses\SuccessResponse;
use Illuminate\Database\Eloquent\Collection;
use MailchimpAPI\Responses\MailchimpResponse;

trait Ecommerce
{

    /**
     * @return MailchimpAPI\Resources\EcommerceStores
     */
    public function ecommerce(): EcommerceStores
    {
        return $this->client
            ->ecommerceStores($this->getStoreId());
    }

    /**
     * @param array $storeData
     *
     * @return MailchimpResponse
     */
    public function createStore(array $storeData): MailchimpResponse
    {
        $requiredKeys = ['name', 'domain', 'email_address', 'currency_code'];
        
        $this->validateArrayKeys($requiredKeys, $storeData);

        if (! in_array('id', array_keys($storeData))) {
            $storeData['id'] = 'store_' . Str::random(2) . '_' . time();
        }

        if (in_array('list_id', array_keys($storeData))) {
            return $this->ecommerce()->post($storeData);
        }

        $response = json_decode($this->client->lists()->get()->getBody());
        $listIds = array_values(array_column($response->lists, 'id'));

        if (count($listIds) > 1) {
            throw new \Exception("List id field is required, please provide  a valid list id from (".implode(', ', $listIds).")");
        }

        $storeData['list_id'] = $listIds[0];

        return $this->ecommerce()->post($storeData);
    }

    /**
     * @param  array       $customerData
     *
     * @return MailchimpResponse
     */
    public function createCustomer(array $customerData): MailchimpResponse
    {
        $customerData = $this->getValidatedCustomerData($customerData);

        return $this->ecommerce($this->getStoreId())
            ->customers()
            ->post($customerData);
    }

    /**
     * @param  Collection $products
     * @param  array      $cartData
     * @param  array      $customerData
     *
     * @return MailchimpResponse
     */
    public function createCart(Collection $products, array $cartData, array $customerData): MailchimpResponse
    {
        $cartData = $this->getValidatedCartData($cartData, $products);
        $cartData['customer'] = $this->getValidatedCustomerData($customerData);

        return $this->ecommerce($this->getStoreId())
            ->carts()
            ->post($cartData);
    }

    /**
     * @param Collection $products
     *
     * @return array
     */
    public function addProducts(Collection $products): array
    {
        $productsData = $products->map(function ($product) {
            $variants = tap($product->children, function ($products) use ($product) {
                $products->push($product);
            })->map(function ($child) {
                return [
                    'id' => (string) $child->id,
                    'title' => $child->name,
                    'url' => route('product.show', $child->slug),
                    'image_url' => $child->main_image,
                    'sku' => $child->sku,
                    'price' => $child->price,
                    'inventory_quantity' => $child->quantity,
                ];
            })
            ->values()
            ->toArray();

            return [
                'id' => (string) $product->id,
                'title' => $product->name,
                'url' => route('product.show', $product->slug),
                'image_url' => $product->main_image,
                'variants' => $variants
            ];
        })
        ->values()
        ->toArray();

        $response = [];

        foreach ($productsData as $product) {
            $apiResponse = $this->ecommerce($this->getStoreId())
                ->products()
                ->post($product);

            $response[] = [
                'id' => $product['id'],
                'status' => $apiResponse instanceof SuccessResponse,
                'response' => $apiResponse,
            ];
        }

        return $response;
    }
}
