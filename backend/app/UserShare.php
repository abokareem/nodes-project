<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserShare extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'share_id',
        'count'
    ];
}
