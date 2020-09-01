<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Item;
use Faker\Generator as Faker;

$factory->define(Item::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence(),
        'description' => $faker->paragraph(),
        'pretax_price' => $faker->numberBetween(1000, 9000),
        'taxes' => $faker->numberBetween(100, 900),
    ];
});
