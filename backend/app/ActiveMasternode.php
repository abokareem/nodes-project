<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActiveMasternode extends Model
{
    const SINGLE_TYPE = 'single';
    const PARTY_TYPE = 'party';
    const PROCESSING_STATE = 'processing';
    const STABLE_STATE = 'stable';
    const UNSTABLE_STATE = 'unstable';
    const DISBAND_STATE = 'disband';

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
