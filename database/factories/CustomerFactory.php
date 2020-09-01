<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Customer;
use Faker\Generator as Faker;

$factory->define(Customer::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'company_code' => $faker->isbn10,
        'vat_code' => $faker->isbn10,
        'country' => $faker->country,
        'address' => $faker->streetAddress,
        'phone_number' => $faker->phoneNumber,
        'email' => $faker->safeEmail,
        'type' => $faker->randomElement([Customer::INDIVIDUAL_TYPE, Customer::ORGANIZATION_TYPE]),
    ];
});