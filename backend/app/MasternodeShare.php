<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MasternodeShare extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'node_id',
        'price',
        'count'
    ];
}
