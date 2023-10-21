<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    /**
     * @var array
     */
    protected $appends = [
        'value',
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'coupon_code',
        'is_active',
        'discount_type',
        'discount_amount',
        'discount_method',
        'expiration_date',
        'activation_date',
        'collects_email',
        'name',
        'description',
        'shipping_id',
        'trigger_amount',
        'is_triggerable',
    ];

    /**
    * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
    */
    public function order()
    {
        return $this->belongsToMany(Order::class);
    }
    
    /**
    * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
    */
    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    public function shipping()
    {
        return $this->belongsTo(Shipping::class);
    }

    /**
     * Get discount target on cart.
     *
     * @return string
     */
    public function getDiscountTypeAttribute()
    {
        return isset($this->attributes['discount_type']) && trim($this->attributes['discount_type']) !== ''
            ? $this->attributes['discount_type']
            : 'subtotal';
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return (int) $this->attributes['is_active'] === 1;
    }

    /**
     * Get discount value from amount set, if discount method is percentage then
     * return the valid percentage value between 0 and 100% else return
     * the amount set for this discount direcly on the table.
     *
     * @return mixed
     */
    public function getValueAttribute()
    {
        if ($this->attributes['discount_method'] === 'percentage') {
            $discountNum = floatval($this->attributes['discount_amount']) >= 1
                ? floatval($this->attributes['discount_amount'])
                : floatval($this->attributes['discount_amount']) * 100;
            return $discountNum . '%';
        }

        return $this->attributes['discount_amount'];
    }
}
