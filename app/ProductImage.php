<?php

namespace App;

use App\Scopes\CachableScope;
use Illuminate\Support\Facades\Storage;

class ProductImage extends Model
{
    /**
     * Fillable attributes
     *
     * @var array
     */
    protected $fillable = [
        'product_id', 'src', 'is_main', 'is_transparent'
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
     * Related product
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class)
                    ->remember(self::getDefaultCacheTime());
    }

    /**
     * Get main image
     *
     * @return string
     */
    public function getSrcAttribute()
    {
        $src = $this->attributes['src'] ?? $this->src ?? '';

        if (trim($src) !== '' && count(explode('.', $src)) <= 1) {
            return \Bkwld\Croppa\Facade::url(
                config('default-variables.default-image')
            );
        }

        $imageLink = ! Storage::disk('public')->exists($src)
                     ? config('default-variables.default-image')
                     : 'storage/' . $src;

        return \Bkwld\Croppa\Facade::url($imageLink);
    }
}
