<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\InvoiceSerie;
use Faker\Generator as Faker;

$factory->define(InvoiceSerie::class, function (Faker $faker) {
    return [
        'prefix' => $faker->lexify('????'),
    ];
});
