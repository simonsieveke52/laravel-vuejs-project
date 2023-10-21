<?php

namespace App;

use \DateTimeInterface;
use Watson\Rememberable\Rememberable;
use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * The Easy way to use cachable queries
 */
abstract class Model extends Eloquent
{
    use Rememberable;

    /**
     * Get status attribute
     *
     * @return float
     */
    public function getStatusAttribute()
    {
        try {
            if (isset($this->attributes['status'])) {
                return (int) $this->attributes['status'];
            }

            if ($this->exists && isset($this->status)) {
                return $this->status;
            }
            
            return 0;
        } catch (\Exception $exception) {
            logger($exception->getMessage());
            return 0;
        }
    }
    
    /**
     * Default models cache life time
     *
     * @return int
     */
    public static function getDefaultCacheTime(): int
    {
        return (int) config('default-variables.cache_life_time');
    }

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
