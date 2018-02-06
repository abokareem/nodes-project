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
