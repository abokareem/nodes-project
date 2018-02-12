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

    public function getAuthUserDataAttribute($value)
    {
        if ($value) {
            return json_decode($value);
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
