<?php

namespace App;

use Illuminate\Support\Str;
use App\Scopes\EnabledScope;
use App\Scopes\CachableScope;
use Kalnoy\Nestedset\NodeTrait;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use NodeTrait, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id', 'depth'
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope(new EnabledScope());
        static::addGlobalScope(new CachableScope());
    }

    /**
     * Get main image attribute
     *
     * @param  mixed $value
     *
     * @return string
     */
    public function getCoverAttribute($value)
    {
        if (Storage::disk('public')->exists(str_replace('storage/', '', $value))) {
            return \Bkwld\Croppa\Facade::url($value);
        }

        return \Bkwld\Croppa\Facade::url(config('default-variables.default-image'));
    }

    /**
     * @return string
     */
    public function getNameAttribute()
    {
        return html_entity_decode($this->attributes['name'] ?? '');
    }

    /**
     * Router will use this column to find our category
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Child categories
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany(static::class, 'parent_id', 'id')
                    ->orderBy('sort_order')
                    ->remember(self::getDefaultCacheTime());
    }

    /**
     * Parent Category
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(static::class, 'parent_id', 'id')
                    ->orderBy('sort_order')
                    ->remember(self::getDefaultCacheTime());
    }

    /**
     * Related products
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products()
    {
        return $this->belongsToMany(Product::class)
                    ->remember(self::getDefaultCacheTime());
    }

    /**
     * Related products
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function parentProducts()
    {
        return $this->belongsToMany(Product::class)
                    ->where('parent_id', null)
                    ->remember(self::getDefaultCacheTime());
    }

    /**
     * Set slug attribute
     *
     * @param string $value
     */
    public function setSlugAttribute($value)
    {
        if (trim($value) === '') {
            $value = $this->attributes['name'] ?? $this->attributes['id'] ?? strtotime('now');
        }

        $this->attributes['slug'] = Str::slug($value);
    }

    /**
     * Categories displayed on navbar
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOnNavbar($query)
    {
        return $query->where('on_navbar', true)
            ->orderBy('sort_order');
    }

    /**
     * Categories displayed on filter
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOnFilter($query)
    {
        return $query->where('on_filter', true)
            ->orderBy('sort_order');
    }

    /**
     * Categories displayed on home page
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOnHome($query)
    {
        return $query->where('on_home', true)
            ->orderBy('sort_order');
    }
}
