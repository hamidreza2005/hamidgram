<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\View;
use Faker\Generator as Faker;

$factory->define(View::class, function (Faker $faker) {
    $user_ids = \App\User::pluck('id')->toArray();
    $post_ids = \App\Post::pluck('id')->toArray();
    return [
        'user_id'=>\Illuminate\Support\Arr::random($user_ids),
        'post_id'=>\Illuminate\Support\Arr::random($post_ids),
        'viewed_at'=>now()
    ];
});
