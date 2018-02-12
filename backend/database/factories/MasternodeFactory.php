<?php

use Faker\Generator as Faker;
use App\Masternode;

$factory->define(Masternode::class, function (Faker $faker) {
    return [
        'name' => $faker->name(),
        'description' => $faker->text(),
        'state' => $faker->randomElement(['new', 'processing', 'deactivate', 'active']),
        'income' => $faker->randomFloat(),
        'price' => $faker->randomFloat()
    ];
});
