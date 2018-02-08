<?php

use Faker\Generator as Faker;
use App\Currency;

$factory->define(Currency::class, function (Faker $faker) {
    return [
        'name' => $faker->currencyCode,
        'symbol' => '$'
    ];
});
