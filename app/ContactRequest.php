<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactRequest extends Model
{
    use SoftDeletes, Notifiable;
    
    /**
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'phone', 'content'
    ];
}
