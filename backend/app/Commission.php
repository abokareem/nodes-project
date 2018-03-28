<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    const REPLENISH = 'replenish';
    const FOR_SINGLE_NODE = 'single';
    const FOR_PARTY_NODE = 'party';
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
