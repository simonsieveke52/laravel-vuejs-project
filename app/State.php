<?php

namespace App;

use App\Scopes\EnabledScope;
use App\Scopes\CachableScope;

class State extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name', 'abv', 'status', 'country_id'
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
     * @return string
     */
    public function getStateAttribute()
    {
        return ucwords(strtolower($this->attributes['name']));
    }

    /**
     * @return string
     */
    public function getAbvAttribute()
    {
        return strtoupper($this->attributes['abv']);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function zipcodes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Zipcode::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function Cities(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(City::class);
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
