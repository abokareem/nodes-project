<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TwoFaAuth extends Model
{
    protected $fillable = [
        'user_id',
        'token',
        'auth_user_data'
    ];

    public function setAuthUserDataAttribute($value)
    {
        if ($value) {
            $this->attributes['auth_user_data'] = json_encode($value);
        }
    }

    public function getAuthUserDataAttribute($value)
    {
        if ($value) {
            return json_decode($value);
        }
    }
}
