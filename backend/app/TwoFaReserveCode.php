<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TwoFaReserveCode extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'code',
        'state',
        'tries'
    ];

    /**
     * @param $value
     */
    public function setCodeAttribute($value)
    {
        $this->attributes['code'] = encrypt($value);
    }

    /**
     * @param $value
     * @return string
     */
    public function getCodeAttribute($value)
    {
        return decrypt($value);
    }
}
