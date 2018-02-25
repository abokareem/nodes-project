<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WithdrawalMoney extends Model
{
    use SoftDeletes;

    const PROCESSING = 'processing';
    const APPROVE = 'approve';
    const DECLINE = 'decline';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_bill_id',
        'external_user_wallet',
        'state',
        'amount'
    ];
}
