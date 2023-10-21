<?php

namespace App;

use App\Scopes\CachableScope;

class Review extends Model
{
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
        'product_id',
        'name',
        'email',
        'title',
        'description',
        'grade',
        'like_counter',
        'dislike_counter',
        'report_counter',
    ];

    /**
     * @var array
     */
    protected $hidden = [
        'email'
    ];
    
    /**
     * @var array
     */
    protected $appends = [
        'formatted_date'
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
        return $this->belongsTo(Product::class);
    }

    /**
     * @param  mixed $value
     *
     * @return float
     */
    public function getGradeAttribute($value)
    {
        if (floatval($value) > 5) {
            return 5;
        }

        return floatval($value);
    }

    /**
     * @param  string $value
     *
     * @return string
     */
    public function getNameAttribute($value): string
    {
        return trim(htmlentities(strip_tags($value)));
    }

    /**
     * @param  string $value
     *
     * @return string
     */
    public function getTitleAttribute($value): string
    {
        return trim(htmlentities(strip_tags($value)));
    }

    /**
     * @param  string $value
     *
     * @return string
     */
    public function getDescriptionAttribute($value): string
    {
        return trim(htmlentities(strip_tags($value)));
    }

    /**
     * @return string
     */
    public function getFormattedDateAttribute()
    {
        if (! $this->created_at) {
            return '';
        }

        return $this->created_at->format('M d, Y');
    }
}
