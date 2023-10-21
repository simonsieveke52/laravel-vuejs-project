<?php

namespace App;

use App\Scopes\EnabledScope;
use App\Scopes\CachableScope;

class Zipcode extends Model
{
    /**
     * All fillable attributes
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name', 'state_id', 'country_id', 'tax_rate'
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope(new EnabledScope());
        static::addGlobalScope(new CachableScope());
    }

    /**
     * Router will use this column to find the zipcode
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'name';
    }

    /**
     * Get tax rate
     *
     * @param  string $value
     *
     * @return float
     */
    public function getTaxRateAttribute($value)
    {
        return config('default-variables.tax_status') ? floatval($value) : 0;
    }

    /**
     * Zipcode state
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function state()
    {
        return $this->belongsTo(State::class);
    }

    /**
     * Zipcode City
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Zipcode Country
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Scope for united states data
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnitedStates($query)
    {
        return $query->where('country_id', 1);
    }
}
