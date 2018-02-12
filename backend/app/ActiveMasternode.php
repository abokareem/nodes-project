<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActiveMasternode extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'masternode_id'
    ];
}
