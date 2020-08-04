<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Post;
use Faker\Generator as Faker;

$factory->define(Post::class, function (Faker $faker) {
    $user_ids = \App\User::pluck('id')->toArray();
    return [
        'user_id'=>\Illuminate\Support\Arr::random($user_ids),
        'url'=>'/2020/default.png',
        'description'=>$faker->paragraph,
        'show_num_of_likes_to_all'=>$faker->boolean(80),
        'just_for_creator'=>$faker->boolean(20),
    ];
});
