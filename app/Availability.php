<?php

namespace App;

use App\Scopes\CachableScope;
use Illuminate\Support\Collection;

class Availability extends Model
{
    /**
     * Fillable attributes
     *
     * @var Array
     */
    protected $fillable = [
        'status',
        'name',
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
     * All related products
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany(Product::class)
            ->remember(self::getDefaultCacheTime());
    }
}
