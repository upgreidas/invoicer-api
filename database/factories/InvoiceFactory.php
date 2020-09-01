<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Invoice;
use Faker\Generator as Faker;

$factory->define(Invoice::class, function (Faker $faker) {
    return [
        'number' => $faker->numberBetween(1, 9999),
        'date' => $faker->date(),
        'due_date' => $faker->date(),
        'notes' => $faker->paragraph(),
        'subtotal' => $faker->numberBetween(1000, 9000),
        'taxes' => $faker->numberBetween(100, 900),
    ];
});