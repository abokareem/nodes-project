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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function share()
    {
        return $this->hasOne(MasternodeShare::class);
    }

    public function bill()
    {
        return $this->hasOne(MasternodeBill::class);
    }
}
