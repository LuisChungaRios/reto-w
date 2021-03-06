<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Product;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'price' => $faker->randomNumber(2),
        'discount' => rand(0,50),
        //'image' => $faker->imageUrl(500,500),
        'category_id' => factory(\App\Category::class)->create()->id
    ];
});
