<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Share extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'currency_id',
        'min_price',
        'full_price'
    ];
}
