<?php

use Faker\Generator as Faker;
use App\Masternode;

$factory->define(Masternode::class, function (Faker $faker) {
    return [
        'currency_id' => $faker->randomNumber(1) + 1,
        'name' => $faker->name(),
        'description' => $faker->text(),
        'income' => $faker->randomFloat(),
        'price' => $faker->randomFloat()
    ];
});
