<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubscriptionHistory extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'order_id',
        'product_id',
        'response',
        'transaction_id',
        'transaction_details',
        'status'
    ];

    /**
     * @var array
     */
    protected $casts = [
        'response' => 'json',
        'transaction_details' => 'json'
    ];
}
