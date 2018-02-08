<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Masternode extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'state',
        'income',
        'price'
    ];

    public function share()
    {
        return $this->hasOne(MasternodeShare::class);
    }
}
