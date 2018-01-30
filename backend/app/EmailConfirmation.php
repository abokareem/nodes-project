<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\EmailConfirmation
 *
 * @mixin \Eloquent
 */
class EmailConfirmation extends Model
{
    const CONFIRMED = 1;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'token',
        'email_confirmed_tries'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getRouteKeyName()
    {
        return 'token';
    }

}
