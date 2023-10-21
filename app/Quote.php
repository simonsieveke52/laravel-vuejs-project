<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quote extends Model
{
    use SoftDeletes;
    
    /**
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'phone', 'content'
    ];
}
