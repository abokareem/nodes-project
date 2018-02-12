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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function share()
    {
        return $this->hasOne(ActiveMasternodeShares::class, 'node_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function bill()
    {
        return $this->hasOne(MasternodeBill::class, 'node_id', 'id');
    }
}
