<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserProfit extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'node_id',
        'per_day',
        'amount'
    ];
}
