<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Withdrawals extends Model
{
    const PROCESSING_STATE = 'processing';
    const APPROVE_STATE = 'approve';
    const DECLINE_STATE = 'decline';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'node_id',
        'state',
        'amount'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function node()
    {
        return $this->belongsTo(Masternode::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
