<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'currency_id',
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
}
