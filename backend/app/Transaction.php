<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    const SETTLEMENT_MESSAGE = 'transaction.type.settlement';
    const REPLENISH_BILL_MESSAGE = 'transaction.type.replenish_bill';
    const WITHDRAW_BILL_MESSAGE = 'transaction.type.withdraw_bill';
    const INCOME_MESSAGE = 'transaction.type.income';
    const BUY_SHARE_MESSAGE = 'transaction.type.buy_share';
    const WITHDRAWAL_NODE_MESSAGE = 'transaction.type.withdrawal_node';

    const SETTLEMENT_TYPE = 'settlement';
    const REPLENISH_BILL_TYPE = 'replenish_bill';
    const WITHDRAW_BILL_TYPE = 'withdraw_bill';
    const INCOME_TYPE = 'income';
    const BUY_SHARE_TYPE = 'buy_share';
    const WITHDRAWAL_NODE_TYPE = 'withdrawal_node';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'currency_id',
        'type',
        'message',
        'data',
        'amount'
    ];

    /**
     * @param $value
     */
    public function setDataAttribute($value)
    {
        if ($value) {
            $this->attributes['data'] = json_encode($value);

            return;
        }

        $this->attributes['data'] = $value;
    }

    /**
     * @param $value
     * @return string
     */
    public function getDataAttribute($value)
    {
        if ($value) {
            return json_decode($value);
        }
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
}
