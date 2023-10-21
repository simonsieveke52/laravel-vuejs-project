<?php

namespace App;

use Illuminate\Support\Arr;
use App\Custom\ShoppingCart;
use App\Repositories\CartRepository;

class Shipping extends Model
{
    /**
     * @var array
     */
    protected $appends = [
        'cost',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'base_cost', 'status'
    ];


    /**
     * Get shipping cost
     *
     * @return float|null
     */
    public function getCostAttribute(): ?float
    {
        return 0;
    }

    /**
     * @return float
     */
    public function getBaseCostAttribute()
    {
        return floatval($this->attributes['base_cost'] ?? $this->base_cost ?? 0);
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', true);
    }
}
