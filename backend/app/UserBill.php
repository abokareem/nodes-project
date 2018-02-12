<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserBill extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'currency_id',
        'user_id',
        'uuid',
        'amount'
    ];
}
