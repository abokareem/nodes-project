<?php

use Faker\Generator as Faker;

$factory->define(\App\MasternodeShare::class, function (Faker $faker) {
    return [
        'price' => $faker->randomFloat(),
        'count' => $faker->randomNumber()
    ];
});
