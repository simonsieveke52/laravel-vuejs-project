<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use SoftDeletes;

    /**
     * Soft delete date
     *
     * @var array
     */
    protected $dates = [
        'deleted_at'
    ];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type',
        'status',
        'zipcode',
        'city',
        'city_id',
        'order_id',
        'state_id',
        'address_1',
        'address_2',
        'country_id',
        'customer_id',
        'validated_response',
    ];

    /**
     * @return string
     */
    public function getAddress1Attribute()
    {
        return ucwords(strtolower($this->attributes['address_1'] ?? ''));
    }

    /**
     * @return string
     */
    public function getAddress2Attribute()
    {
        return ucwords(strtolower($this->attributes['address_2'] ?? ''));
    }

    /**
     * @return string
     */
    public function getCityAttribute()
    {
        return ucwords(strtolower($this->attributes['city'] ?? ''));
    }

    /**
     * Country associated
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country()
    {
        return $this->belongsTo(Country::class)->withDefault();
    }

    /**
     * State associated
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function state()
    {
        return $this->belongsTo(State::class)->withDefault();
    }

    /**
     * Order associated
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * @param  Builder $query
     * @return Builder
     */
    public function scopeValidated($query)
    {
        return $query->where('type', 'validated');
    }
}
