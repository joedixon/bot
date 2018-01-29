<?php

use Faker\Generator as Faker;

$factory->define(App\Article::class, function (Faker $faker) {
    return [
        'unique_id' => $faker->unique()->randomNumber(),
        'title' => $faker->word,
        'description' => $faker->sentence,
        'url' => $faker->url,
        'image_url' => $faker->url,
        'has_been_sent' => true,
    ];
});
