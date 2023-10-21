<?php

namespace App;

class OrderApiResponse extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'order_id', 'product_id', 'caller', 'content', 'status',
    ];

    /**
     * @return array|null
     */
    public function getContentAttribute()
    {
        if (isset($this->attributes) && isset($this->attributes['content'])) {
            return json_decode($this->attributes['content']);
        }

        if (isset($this->content) && is_string($this->content)) {
            return json_decode($this->content);
        }

        return null;
    }
}
