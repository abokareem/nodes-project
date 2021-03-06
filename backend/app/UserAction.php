<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserAction extends Model
{
    const REGISTER = 'logs.user.register';
    const RESET_PASSWORD = 'logs.user.resetPassword';
    const LOGIN = 'logs.user.login';
    const CONFIRMED_EMAIL = 'logs.user.confirmedEmail';
    const RESEND_CONFIRMED_EMAIL = 'logs.user.resendConfirmedEmail';
    const TWO_FA_ENABLED = 'logs.user.twofaEnabled';
    const TWO_FA_DISABLED = 'logs.user.twofaDisabled';
    const TWO_FA_RESET = 'logs.user.twofaReset';
    const UPDATE_PROFILE = 'logs.user.update';
    const BUY_SHARES = 'logs.share.buy';
    const MASTERNODE_CREATED = 'logs.node.create';
    const PUT_MONEY = 'logs.bill.put';
    const WITHDRAWAL_MONEY = 'logs.bill.withdrawal';
    const ACCEPTED_LEAVE_FROM_NODE = 'logs.node.gone';
    const WITHDRAWAL_CREATED = 'logs.node.withdrawal';
    const WITHDRAWAL_DECLINED = 'logs.node.declined';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'message',
        'ip',
        'agent'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
