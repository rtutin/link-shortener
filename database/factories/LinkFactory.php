<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Link;
use Faker\Generator as Faker;

$factory->define(Link::class, function (Faker $faker) {
    return [
        'url' => $faker->url,
        'expiration_to' => $faker->dateTimeBetween('now', strtotime('+1 day'))
    ];
});
