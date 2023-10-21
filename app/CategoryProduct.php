<?php

namespace App;

use App\Scopes\CachableScope;

class CategoryProduct extends Model
{
    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var string
     */
    protected $table = 'category_product';

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
     * Related products
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products()
    {
        return $this->HasMany(Product::class, 'id', 'product_id')
            ->remember(config('default-variables.cache_life_time'));
    }
}
