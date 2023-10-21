<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;

class Carrier extends Model
{
    use SoftDeletes;

    /**
     * @var array
     */
    protected $fillable = [
        'order_id',
        'service_name',
        'service_code',
        'shipment_cost',
        'other_cost',
        'carrier_code',
    ];

    /**
     * @var array
     */
    protected $appends = [
        'shipstation_carrier_code',
        'shipstation_service_code',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * @return string
     */
    public function getShipstationCarrierCodeAttribute()
    {
        if (! isset($this->attributes['service_code'])) {
            return '';
        }

        $index = array_search($this->attributes['service_code'], array_column(config('ups.services'), 'Code'));

        if ($index === false) {
            return '';
        }

        return isset(config('ups.services')[$index])
            ? trim(config('ups.services')[$index]['shipstation_carrier_code'] ?? '')
            : '';
    }

    /**
     * @return string
     */
    public function getShipstationServiceCodeAttribute()
    {
        if (! isset($this->attributes['service_code'])) {
            return '';
        }

        $index = array_search($this->attributes['service_code'], array_column(config('ups.services'), 'Code'));

        if ($index === false) {
            return '';
        }

        return isset(config('ups.services')[$index])
            ? trim(config('ups.services')[$index]['shipstation_service_code'] ?? '')
            : '';
    }
}
