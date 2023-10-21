<?php

namespace App;

use App\Product;
use Spatie\Tags\HasTags;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Scopes\EnabledScope;
use App\Scopes\CachableScope;
use Kalnoy\Nestedset\NodeTrait;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasTags, NodeTrait, SoftDeletes, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id'
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = [
        'availability', 'images'
    ];

    /**
     * @var array
     */
    protected $appends = [
        'msrp',
        'status_name',
        'selling_price',
        'original_price',
        'description_text',
    ];

    /**
     * Searchable columns
     *
     * @var array
     */
    protected $searchableColumns = [
        'searchable_text', 'name', 'id', 'sku', 'upc', 'description'
    ];

    /**
     * @var array
     */
    protected static $freeShippingOptions = [
        "Not free shipping",
        "Free Ground",
        "Free 2-day"
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
     * @return array
     */
    public static function getFreeShippingOptions()
    {
        return self::$freeShippingOptions;
    }

    /**
     * Get main image attribute
     *
     * @param  mixed $value
     *
     * @return string
     */
    public function getMainImageAttribute($value)
    {
        $image = trim(str_replace('storage/', '', $value));

        if (Str::startsWith($image, '/')) {
            $image = substr($image, 1);
        }

        $imagePath = str_replace('//', '/', \Bkwld\Croppa\Facade::url($image));

        if (Storage::disk('public')->exists($imagePath)) {
            return Str::startsWith($imagePath, '/')
                ? substr($imagePath, 1)
                : $imagePath;
        }

        $image = $this->images->sortBy('is_main')->first();
        
        $imagePath = is_null($image)
            ? config('default-variables.default-image')
            : str_replace('//', '/', trim(str_replace('storage/', '', $image->src)));

        return Str::startsWith($imagePath, '/')
                ? substr($imagePath, 1)
                : $imagePath;
    }

    /**
     * @return string
     */
    public function getDescriptionTextAttribute()
    {
        $description = strip_tags(trim($this->attributes['description'] ?? ''));

        if (trim(str_replace('&nbsp;', '', $description)) === '') {
            return '';
        }

        return html_entity_decode($description);
    }

    /**
     * Get weight
     *
     * @param string $value
     *
     * @return string
     */
    public function getQuantityAttribute($value)
    {
        return (int) $value;
    }

    /**
     * Get weight
     *
     * @param string $value
     *
     * @return string
     */
    public function getWeightUomAttribute($value)
    {
        if (! in_array(trim($value), ['pounds', 'ounces', 'grams'])) {
            $value = 'pounds';
        }

        return $value;
    }

    /**
     * Get weight
     *
     * @param  float $value
     * @return float
     */
    public function getWeightAttribute($value)
    {
        return trim($value) !== '' ? floatval($value) : 3;
    }

    /**
     * Get length
     *
     * @param  float $value
     * @return float
     */
    public function getLengthAttribute($value)
    {
        return trim($value) !== '' ? floatval($value) : 12;
    }

    /**
     * Get height
     *
     * @param  float $value
     * @return float
     */
    public function getHeightAttribute($value)
    {
        return trim($value) !== '' ? floatval($value) : 12;
    }

    /**
     * Get width
     *
     * @param  float $value
     * @return float
     */
    public function getWidthAttribute($value)
    {
        return trim($value) !== '' ? floatval($value) : 12;
    }

    /**
     * @return string
     */
    public function getNameAttribute()
    {
        return html_entity_decode($this->attributes['name'] ?? '');
    }

    /**
     * @return string
     */
    public function getOptionNameAttribute()
    {
        if (isset($this->attributes['option_name']) && strlen($this->attributes['option_name']) > 0) {
            return html_entity_decode($this->attributes['option_name']);
        }

        $nameArray = explode(' ', $this->getNameAttribute());

        return collect(Arr::wrap($nameArray))->take(-2)->first();
    }

    /**
     * Get whether this product is a parent, child, or standalone.
     *
     * @return string
     */
    public function getNestedTypeAttribute()
    {
        if (isset($this->attributes['parent_id']) && (int) $this->attributes['parent_id'] !== 0) {
            return 'child';
        }
        
        if ($this->children()->count() === 0) {
            return 'standalone';
        }

        return 'parent';
    }

    /**
     * @return float
     */
    public function getMsrpAttribute()
    {
        return isset($this->attributes['original_price'])
            ? floatval($this->attributes['original_price'])
            : 0;
    }

    /**
     * Get original price (MSRP)
     *
     * @return float
     */
    public function getOriginalPriceAttribute()
    {
        return $this->getRawOriginalPrice();
    }

    /**
     * @return float
     */
    protected function getRawOriginalPrice()
    {
        return isset($this->attributes['price'])
            ? $this->attributes['price']
            : 0;
    }

    /**
     * Get Price attribute
     *
     * @return float
     */
    public function getSellingPriceAttribute()
    {
        return isset($this->attributes['price']) && floatval($this->attributes['price']) !== 0
            ? $this->attributes['price']
            : 0;
    }

    /**
     * Get Price attribute (Selling pirce)
     *
     * @return float
     */
    public function getPriceAttribute()
    {
        return $this->getSellingPriceAttribute();
    }

    /**
     * @return string
     */
    public function getShortDescriptionAttribute()
    {
        if (isset($this->attributes['short_description']) && trim($this->attributes['short_description']) !== '') {
            return $this->attributes['short_description'];
        }

        if (isset($this->attributes['description']) && trim($this->attributes['description']) !== '') {
            return html_entity_decode($this->attributes['description']);
        }

        return trim($this->attributes['name']);
    }

    /**
     * @return float
     */
    public function getCostAttribute()
    {
        return round(($this->attributes['cost'] ?? 0), 2);
    }

    /**
     * Product categories
     *
     * @return Category|null
     */
    public function getCategoryAttribute()
    {
        try {
            return $this->categories()->orderBy('id', 'desc')->first();
        } catch (\Exception $exception) {
            return null;
        }
    }

    /**
     * Product parent categories
     *
     * @return \Illuminate\Support\Collection
     */
    public function getParentCategoriesAttribute()
    {
        if (! isset($this->category)) {
            return collect();
        }

        if (! ($this->category instanceof Category)) {
            return collect();
        }

        return Category::ancestorsAndSelf($this->category);
    }

    /**
     * @return bool
     */
    public function getIsFreeShippingAttribute()
    {
        return isset($this->attributes['is_free_shipping']) ? (bool) $this->attributes['is_free_shipping'] : false;
    }

    /**
     * @return \stdClass
     */
    public function getFreeShippingServiceAttribute(): \stdClass
    {
        $label = $this->attributes['free_shipping_option'] ?? $this->free_shipping_option ?? '';

        if (trim($label) === '') {
            $label = 'UPS Ground';
        }

        $key = Str::slug($label);

        $response = [];

        $response[$key]['label'] = $label;
        $response[$key]['slug'] = $key;
        $response[$key]['serviceCode'] = strtolower($response[$key]['label']) === 'free ground' ? '03' : '02';
        $response[$key]['cost'] = 0;

        return (Object) $response[$key];
    }

    /**
     * Get status name attribute
     *
     * @return string
     */
    public function getStatusNameAttribute(): string
    {
        $status = $this->getStatusAttribute();

        switch ($status) {
            case 0:
                return 'Disabled';

            case 1:
                return 'Enabled';

            default:
                return 'Draft';
        }
    }

    /**
     * Get AVG reviews for this product
     *
     * @return float
     */
    public function getReviewAvgAttribute()
    {
        if (isset($this->attributes['review_avg']) && floatval($this->attributes['review_avg']) > 0) {
            return floatval($this->attributes['review_avg']);
        }

        try {
            return $this->reviews()->avg('grade');
        } catch (\Exception $exception) {
            logger($exception->getMessage());
            return 0;
        }
    }

    /**
     * Get Total reviews for this product
     *
     * @return int
     */
    public function getReviewCountAttribute()
    {
        if (isset($this->attributes['review_count']) && (int) $this->attributes['review_count'] > 0) {
            return (int) $this->attributes['review_count'];
        }

        try {
            return $this->reviews()->count();
        } catch (\Exception $exception) {
            logger($exception->getMessage());
            return 0;
        }
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

        if (isset($this->attributes['name'])) {
            $this->attributes['searchable_text'] = $this->attributes['name'];
        }

        $this->attributes['slug'] = Str::slug($value);
    }
    
    /**
     * @param mixed $value
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = trim($value);
        $this->attributes['searchable_text'] = trim(html_entity_decode(strip_tags($value)));
    }

    /**
     * @param mixed $value
     */
    public function setSearchableTextAttribute($value)
    {
        try {
            $this->attributes['searchable_text'] = trim(html_entity_decode(strip_tags($value)));

            if ($this->attributes['searchable_text'] === '' && isset($this->attributes['name'])) {
                $this->attributes['searchable_text'] = trim($this->attributes['name']);
            }

            if ($this->attributes['searchable_text'] === '' && isset($this->attributes['description'])) {
                $this->attributes['searchable_text'] = trim(html_entity_decode(strip_tags($this->attributes['description'])));
            }
        } catch (\Exception $exception) {
            logger($exception->getMessage());
        }
    }

    /**
     * Set status
     *
     * @param string $value
     */
    public function setStatusAttribute($value)
    {
        if (in_array(strtolower($value), ['on', 'off'])) {
            $value = strtolower($value) === 'on' ? 1 : 0;
        }

        $this->attributes['status'] = $value;
    }

    /**
     * @param mixed $value
     */
    public function setCostAttribute($value)
    {
        try {
            $value = preg_replace('/[^0-9.]/', '', $value);
            $this->attributes['cost'] = round(floatval($value), 2);
        } catch (\Exception $exception) {
            logger($exception->getMessage());
        }
    }

    /**
     * @param mixed $value
     */
    public function setPriceAttribute($value)
    {
        try {
            $value = preg_replace('/[^0-9.]/', '', $value);
            $this->attributes['price'] = round(floatval($value), 2);
        } catch (\Exception $exception) {
            logger($exception->getMessage());
        }
    }

    /**
     * @param mixed $value
     */
    public function setOriginalPriceAttribute($value)
    {
        try {
            $value = preg_replace('/[^0-9.]/', '', $value);
            $this->attributes['original_price'] = round(floatval($value), 2);
        } catch (\Exception $exception) {
            logger($exception->getMessage());
        }
    }

    /**
     * @param mixed $value
     */
    public function setMapPriceAttribute($value)
    {
        try {
            $value = preg_replace('/[^0-9.]/', '', $value);
            $this->attributes['map_price'] = round(floatval($value), 2);
        } catch (\Exception $exception) {
            logger($exception->getMessage());
        }
    }

    /**
     * @param mixed $value
     */
    public function setIsMapEnabledAttribute($value)
    {
        switch (trim(strtolower($value))) {
            case 'on':
                $this->attributes['is_map_enabled'] = 1;
                break;

            case 'off':
                $this->attributes['is_map_enabled'] = 0;
                break;
            
            default:
                $this->attributes['is_map_enabled'] = is_null($value) ? 0 : $value;
                break;
        }
    }
    
    /**
     * @param mixed $value
     */
    public function setIsOnFeedAttribute($value)
    {
        switch (trim(strtolower($value))) {
            case 'on':
                $this->attributes['is_on_feed'] = 1;
                break;

            case 'off':
                $this->attributes['is_on_feed'] = 0;
                break;
            
            default:
                $this->attributes['is_on_feed'] = is_null($value) ? 0 : $value;
                break;
        }
    }

    /**
     * Relation to the parent.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Product', $this->getParentIdName())
            ->setModel($this)
            ->withoutGlobalScopes([EnabledScope::class]);
    }

    /**
     * Relation to children.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Product', $this->getParentIdName())
            ->setModel($this)
            ->withoutGlobalScopes([EnabledScope::class]);
    }

    /**
     * Get product options
     */
    public function scopeOptions($query)
    {
        $type = $this->getNestedTypeAttribute();

        switch ($type) {
            case 'child':
                $parent = $this->parent()->first();
                $childs = $parent->children()->where('id', '!=', $this->attributes['id'] ?? '')->get();
                return $childs->push($parent);

            case 'standalone':
                return collect();

            default:
                return $this->children()->get();
        }
    }

    /**
     * Product availability
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function availability()
    {
        return $this->belongsTo(Availability::class);
    }

    /**
     * Product brand
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Product manufacture
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function manufacture()
    {
        return $this->belongsTo(Manufacture::class);
    }

    /**
     * Product country
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Product images
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('is_main', 'desc');
    }

    /**
     * Product orders
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function Orders()
    {
        return $this->belongsToMany(Order::class, Product::class, 'id', 'id')
                    ->withPivot(['quantity', 'options', 'price'])
                    ->remember(self::getDefaultCacheTime());
    }

    /**
     * Product Reviews
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * @throws \Exception
     *
     * @return self
     */
    public function refreshReviews()
    {
        if (! isset($this->exists) || ! $this->exists) {
            throw new \Exception("Invalid method call {refreshReviews}");
        }

        $this->attributes['review_avg'] = round($this->reviews()->avg('grade'), 2);
        $this->attributes['review_count'] = (int) $this->reviews()->count();
        $this->save();

        return $this;
    }

    /**
     * Product categories
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_product', 'product_id');
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEnabled($query)
    {
        return $query->where('status', true);
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeParents($query)
    {
        return $query->has('children');
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAvailable($query)
    {
        return $query->where('availability_id', 1);
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBestSeller($query)
    {
        return $query->orderBy('clicks_counter', 'desc')
            ->orderBy('sales_count', 'desc')
            ->orderBy('review_avg', 'desc');
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  mixed $searchTerm
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, $searchTerm)
    {
        $terms = array_map(
            function ($value) {
                return trim(strtolower(strip_tags($value)));
            },
            explode(' ', strip_tags(trim(strtolower($searchTerm))))
        );

        $searchableColumns = $this->searchableColumns;

        return $query
            ->whereNull('deleted_at')
            ->where(function ($query) use ($searchTerm, $terms, $searchableColumns) {
                return $query->where(function ($query) use ($searchTerm, $terms, $searchableColumns) {
                    for ($i = 0; $i < count($terms) - 1; $i++) {
                        foreach (['searchable_text', 'name'] as $column) {
                            $query = $query->orWhere(function ($query) use ($terms, $column) {
                                $query = $query->orWhereRaw('LOWER('.$column.') LIKE ? ', ['%' . implode('%', $terms) . '%']);

                                $singularTerms = array_map(function ($term) {
                                    return Str::singular($term);
                                }, $terms);

                                $pluralTerms = array_map(function ($term) {
                                    return Str::plural($term);
                                }, $terms);

                                $query = $query->orWhereRaw('LOWER('.$column.') LIKE ? ', ['%' . implode('%', $singularTerms) . '%']);
                                $query = $query->orWhereRaw('LOWER('.$column.') LIKE ? ', ['%' . implode('%', $pluralTerms) . '%']);
                            });
                        }

                        shuffle($terms);
                    }

                    foreach ($searchableColumns as $column) {
                        $value = trim(strtolower(strip_tags($searchTerm)));
                        $query = $query->orWhereRaw('LOWER('.$column.') LIKE ? ', ['%'.$value.'%']);

                        if (in_array($column, ['searchable_text', 'name'])) {
                            $query = $query->orWhereRaw('LOWER('.$column.') LIKE ? ', ['%'.Str::singular($value).'%']);
                            $query = $query->orWhereRaw('LOWER('.$column.') LIKE ? ', ['%'.Str::plural($value).'%']);
                        }
                    }
                });
            })
            ->orderByRaw("
                CASE WHEN instr(LOWER(searchable_text), '?') = 0 THEN 1 ELSE 0 END,
                instr(LOWER(searchable_text), '?') DESC,
                CHAR_LENGTH(searchable_text) DESC
            ", [trim(strtolower($searchTerm)), trim(strtolower($searchTerm))]);
    }
}
