<?php

namespace App;

use App\Scopes\CachableScope;

class City extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name', 'timezone', 'state_id', 'country_id',
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope(new CachableScope());
    }

    /**
     * Related state
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function state()
    {
        return $this->belongsTo(State::class)
                    ->remember(self::getDefaultCacheTime());
    }

    /**
     * Related zipcodes
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function zipcodes()
    {
        return $this->hasMany(Zipcode::class)
                    ->remember(self::getDefaultCacheTime());
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
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnitedStates($query)
    {
        return $query->where('country_id', 1);
    }
}
