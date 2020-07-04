<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Product;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'description' => $faker->paragraph,
        'category' => $faker->title,
        'price' => $faker->randomFloat($nbMaxDecimals = NULL, $min = 5, $max = 8000),
        'stock' => $faker->randomDigitNotNull(1000),
    ];
});
