<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActiveMasternodeShares extends Model
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function masternode()
    {
        return $this->belongsTo(ActiveMasternode::class,'node_id','id');
    }
}
