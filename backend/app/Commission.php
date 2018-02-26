<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    const REPLENISH = 'replenish';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type',
        'percent'
    ];
}
