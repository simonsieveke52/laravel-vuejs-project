<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrackingNumber extends Model
{
    use Notifiable, SoftDeletes;

    /**
     * @var array
     */
    protected $fillable = [
        'order_id',
        'shipment_id',
        'number',
        'file_name',
        'file_path',
        'shipment_cost',
        'insurance_cost',
        'carrier_name',
        'carrier_code',
        'details'
    ];

    /**
     * @var array
     */
    protected $appends = [
        'url'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * @return string
     */
    public function getUrlAttribute()
    {
        if (! isset($this->attributes['carrier_name']) || trim($this->attributes['carrier_name']) === '') {
            return '';
        }

        if (strpos(strtolower($this->attributes['carrier_name']), 'fedex') !== false) {
            return 'https://www.fedex.com/Tracking?tracknumbers=' . $this->attributes['number'];
        }

        return 'http://wwwapps.ups.com/WebTracking/processInputRequest?TypeOfInquiryNumber=T&InquiryNumber1=' . $this->attributes['number'];
    }
}
