<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FreeWallet extends Model
{
    const FREE_STATE = 'free';
    const BUSY_STATE = 'busy';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'currency_id',
        'state',
        'hash'
    ];
}
