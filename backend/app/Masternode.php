<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Masternode extends Model
{
    const SINGLE_TYPE = 'single';
    const PARTY_TYPE = 'party';
    const PROCESSING_STATE = 'processing';
    const STABLE_STATE = 'stable';
    const UNSTABLE_STATE = 'unstable';
    const DISBAND_STATE = 'disband';
    const NEW_STATE = 'new';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'currency_id',
        'state',
        'type',
        'price'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function bill()
    {
        return $this->hasOne(MasternodeBill::class, 'node_id', 'id');
    }
}
