<?php

namespace FME\ShipStation;

use App\Order;
use App\Address;
use App\Product;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class ShipStationRepository
{
    /**
     * @var ShipStationClient
     */
    protected $client;

    /**
     * @param ShipStationClient $client
     */
    public function __construct(ShipStationClient $client)
    {
        $this->client = $client;
    }

    /**
     * @param  string $endPoint
     * @param  string $method
     * @return mixed
     */
    public function makeRequest($endPoint, $method = 'get', $toArray = false)
    {
        $response = $this->client->setEndpoint($endPoint)
            ->makeRequest($method);

        return json_decode((string) $response->getBody(), $toArray);
    }

    /**
     * Get Request body
     *
     * @param  Order  $order
     * @param  String $carrierCode
     * @param  array  $weight
     * @param  array  $dimensions
     * @return array
     */
    private function getRatesRequestBody(Order $order, String $carrierCode, array $weight, array $dimensions)
    {
        return [
            'carrierCode' => $carrierCode,
            'fromPostalCode' => config('services.shipStation.fromPostalCode'),
            'toState' => $order->shippingAddress->state->abv,
            'toCountry' => 'US',
            'toPostalCode' => $order->shippingAddress->zipcode,
            'toCity' => $order->shippingAddress->city,
            'weight' => $weight,
            'dimensions' => $dimensions
        ];
    }

    /**
     * @param  Order  $order
     * @param  String $carrierCode
     * @param  String|optional $serviceCode
     * @return array
     */
    public function getRatesForCarrier(Order $order, String $carrierCode, $serviceCode = null)
    {
        if (
            ! $order->products->where('is_free_shipping', true)->isEmpty() &&
            $order->products->where('is_free_shipping', true)->count() === $order->products->count()
        ) {
            $response['free_shipping'] = [
                config('services.shipStation.free_shipping')
            ];
            
            return collect($response);
        }

        $weight = $this->getOrderWeight($order);

        $dimensions = $this->getOrderDimensions($order);

        $requestBody = $this->getRatesRequestBody($order, $carrierCode, $weight, $dimensions);

        if (isset($serviceCode) && trim($serviceCode) !== '') {
            $requestBody['serviceCode'] = $serviceCode;
        }

        try {
            $response = $this->client->setEndpoint('shipments/getrates')->makeRequest('POST', [
                'json' => $requestBody
            ]);

            return collect(
                json_decode((string) $response->getBody())
            )
            ->sortBy('shipmentCost')
            ->map(function ($item) use ($carrierCode) {
                $item->carrierCode = $carrierCode;
                return $item;
            })
            ->values();
        } catch (\Exception $e) {
            $response = collect();

            foreach ($order->products as $product) {
                if ((int) $product->is_free_shipping === 1) {
                    continue;
                }

                $response->push(
                    $this->getRatesForProduct($order, $product, $carrierCode, $serviceCode)
                );
            }

            return $response->flatten()->groupBy('serviceCode')->transform(function ($result, $key) {
                $mapped = $result->get(0);
                $mapped->shipmentCost = $result->sum('shipmentCost');
                $mapped->otherCost = $result->sum('otherCost');
                return $mapped;
            })
            ->sortBy('shipmentCost')
            ->map(function ($item) use ($carrierCode) {
                $item->carrierCode = $carrierCode;
                return $item;
            })
            ->values();
        }
    }

    /**
     * @param  Order $order
     * @param  Product $product
     * @param  String $carrierCode
     * @param  String|optional $serviceCode
     * @return array
     */
    public function getRatesForProduct(Order $order, Product $product, String $carrierCode, $serviceCode = null)
    {
        $quantity = isset($product->pivot->quantity) && $product->pivot->quantity > 0 ? $product->pivot->quantity : 1;

        $weight = [
            'value' => $product->weight * $quantity,
            'units' => $product->weight_uom
        ];

        $dimensions = [
            'units' => 'inches',
            'length' => $product->length * $quantity,
            'width' => $product->width * $quantity,
            'height' => $product->height * $quantity
        ];

        $requestBody = $this->getRatesRequestBody($order, $carrierCode, $weight, $dimensions);

        if (isset($serviceCode)) {
            $requestBody['serviceCode'] = $serviceCode;
        }

        try {
            $response = $this->client->setEndpoint('shipments/getrates')->makeRequest('POST', [
                'json' => $requestBody
            ]);

            return json_decode((string) $response->getBody());
        } catch (\Exception $e) {
            $weight = [
                'value' => $product->weight,
                'units' => $product->weight_uom
            ];

            $requestBody = $this->getRatesRequestBody($order, $carrierCode, $weight, [
                'units' => 'inches',
                'length' => $product->length,
                'width' => $product->width,
                'height' => $product->height
            ]);

            $response = $this->client->setEndpoint('shipments/getrates')->makeRequest('POST', [
                'json' => $requestBody
            ]);

            $response = json_decode((string) $response->getBody());

            foreach ($response as &$rate) {
                $rate->shipmentCost *= $quantity;
                $rate->otherCost *= $quantity;
            }

            return $response;
        }
    }

    /**
     * @return
     */
    public function getRates(Order $order, array $carrierCodes)
    {
        $response = [];

        if (
            ! $order->products->where('is_free_shipping', true)->isEmpty() &&
            $order->products->where('is_free_shipping', true)->count() === $order->products->count()
        ) {
            $response['free_shipping'] = [
                config('services.shipStation.free_shipping')
            ];
            
            return collect($response);
        }

        foreach ($carrierCodes as $carrierCode) {
            try {
                $response[$carrierCode] = $this->getRatesForCarrier($order, $carrierCode);
            } catch (\Exception $e) {
                logger($e->getMessage());
            }
        }

        return collect($response);
    }

    /**
     * @return Collection
     */
    public function getCarriers(?Order $order)
    {
        if (isset($order) && $order->exists) {
            if (
                ! $order->products->where('is_free_shipping', true)->isEmpty() &&
                $order->products->where('is_free_shipping', true)->count() === $order->products->count()
            ) {
                $response['free_shipping'] = config('services.shipStation.free_shipping');
                
                return collect($response);
            }
        }

        $self = $this;
        $carriers = collect();

        if (config('services.shipStation.cache')) {
            $carriers = Cache::remember('shipstationrepository-getcarriers', now()->addDay(), function () use ($self) {
                try {
                    return collect(
                        $self->makeRequest('carriers')
                    );
                } catch (\Exception $e) {
                    return collect();
                }
            });
        }

        if (! $carriers->isEmpty()) {
            return $carriers;
        }

        try {
            return collect(
                $this->makeRequest('carriers')
            );
        } catch (\Exception $e) {
            return collect();
        }
    }

    /**
     * @param  Order  $order
     * @return object
     */
    public function createUpdateOrder(Order $order, $callback = null)
    {
        $self = $this;

        $response = $this->client->setEndpoint('/orders/createorder')
            ->makeRequest('POST', [
                'json' => [
                    'orderNumber' => $order->id,
                    'orderKey' => $order->id,
                    'orderDate' => $order->created_at->format('Y-m-d H:i:s'),
                    'paymentDate' => $order->confirmed_at->format('Y-m-d H:i:s'),
                    'paymentMethod' => $order->payment_method,
                    'orderStatus' => 'awaiting_shipment',
                    'customerUsername' => $order->name,
                    'customerEmail' => $order->email,
                    'shipByDate' => now()->addDay()->format('Y-m-d'),
                    'shipDate' => now()->addDay()->format('Y-m-d'),
                    'billTo' => $this->getAddress($order, $order->billingAddress),
                    'shipTo' => $this->getAddress($order, $order->shippingAddress),
                    'amountPaid' => $order->total,
                    'taxAmount' => $order->tax,
                    'shippingAmount' => $order->shipping_cost,
                    'carrierCode' => $order->carrier->carrier_code,
                    'serviceCode' => $order->carrier->service_code,
                    'weight' => $this->getOrderWeight($order),
                    'dimensions' => $this->getOrderDimensions($order),
                    'items' => $order->products->map(function ($product) use ($self) {
                        return $self->getOrderItem($product);
                    })->toArray(),
                ]
            ]);

        $response = json_decode((string) $response->getBody());

        if ($callback instanceof \Closure) {
            $callback($response, $order);
        }

        return $response;
    }

    /**
     * @param  Order  $order
     * @return array
     */
    public function createLabelForOrder(Order $order, $callback = null)
    {
        $response = $this->client->setEndpoint('/orders/createlabelfororder')
            ->makeRequest('POST', [
                'json' => [
                    'orderId' => $order->api_order_id,
                    'carrierCode' => $order->carrier->carrier_code,
                    'serviceCode' => $order->carrier->service_code,
                    'packageCode' => 'package',
                    'confirmation' => null,
                    'shipDate' => now()->addDay()->format('Y-m-d'),
                    'weight' => $this->getOrderWeight($order),
                    'dimensions' => $this->getOrderDimensions($order),
                    'shipFrom' => config('services.shipStation.shipFrom'),
                    'shipTo' => $this->getAddress($order, $order->shippingAddress),
                    'testLabel' => true
                ]
            ]);

        $response = json_decode((string) $response->getBody());

        if ($callback instanceof \Closure) {
            $callback($response, $order);
        }

        return $response;
    }

    /**
     * @param  Order  $order
     * @return void
     */
    protected function getOrderWeight(Order $order)
    {
        $weight = [
            'value' => 0,
            'units' => 'pounds'
        ];

        $order->products->each(function ($product) use (&$dimensions, &$weight) {
            if ((int) $product->is_free_shipping === 1) {
                return true;
            }

            $weight['value'] += ($product->weight * $product->pivot->quantity);
            $weight['units'] = $product->weight_uom;
        });

        return $weight;
    }
    /**
     * @param  Order  $order
     * @return void
     */
    protected function getOrderDimensions(Order $order)
    {
        $dimensions = [
            'units' => 'inches',
            'length' => 0,
            'width' => 0,
            'height' => 0
        ];

        $order->products->each(function ($product) use (&$dimensions, &$weight) {
            if ((int) $product->is_free_shipping === 1) {
                return true;
            }

            $dimensions['length'] += ($product->length * $product->pivot->quantity);
            $dimensions['width'] += ($product->width * $product->pivot->quantity);
            $dimensions['height'] += ($product->height * $product->pivot->quantity);
        });

        return $dimensions;
    }

    /**
     * @param  Product $product
     * @return array
     */
    protected function getOrderItem(Product $product)
    {
        return [
            'name' => $product->name,
            'imageUrl' => asset($product->main_image),
            'weight' => [
                'value' => $product->weight,
                'units' => $product->weight_uom
            ],
            'quantity' => $product->pivot->quantity,
            'unitPrice' => $product->price,
            'warehouseLocation' => 'Aisle 1, Bin 7',
            'productId' => $product->id,
            'fulfillmentSku' => $product->sku,
            'upc' => $product->upc
        ];
    }

    /**
     * @param  Order   $order
     * @param  Address $address
     * @return array
     */
    protected function getAddress(Order $order, Address $address)
    {
        return array_map(function ($item) {
            return is_null($item) ? '' : trim($item);
        }, [
            'name' => $order->name,
            'street1' => $address->address_1,
            'street2' => $address->address_2,
            'city' => $address->city,
            'state' => is_null($address->state) ? '' : $address->state->abv,
            'postalCode' => $address->zipcode,
            'country' => 'US',
            'phone' => $order->phone
        ]);
    }
}
