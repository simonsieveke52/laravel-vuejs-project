<?php

namespace App;

class Manufacture extends Model
{
    /**
     * Fillable attributes
     *
     * @var array
     */
    protected $fillable = [
        'name', 'slug',
    ];

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
