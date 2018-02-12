<?php

use Faker\Generator as Faker;

$factory->define(\App\MasternodeBill::class, function (Faker $faker) {
    return [
        'currency_id' => $faker->randomNumber(1) + 1,
        'amount' => $faker->randomFloat()
    ];
});
