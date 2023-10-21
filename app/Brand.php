<?php

namespace App;

use App\Scopes\CachableScope;
use Illuminate\Support\Collection;

class Brand extends Model
{
    /**
     * Fillable attributes
     *
     * @var Array
     */
    protected $fillable = [
        'name',
        'slug',
        'cover',
        'status'
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
     * @return string
     */
    public function getNameAttribute()
    {
        return html_entity_decode($this->attributes['name'] ?? '');
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
