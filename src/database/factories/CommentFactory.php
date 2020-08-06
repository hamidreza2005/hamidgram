<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Comment;
use Faker\Generator as Faker;

$factory->define(Comment::class, function (Faker $faker) {
    $user_ids = \App\User::pluck('id')->toArray();
    $post_ids = \App\Post::pluck('id')->toArray();
    return [
        'user_id'=>\Illuminate\Support\Arr::random($user_ids),
        'post_id'=>\Illuminate\Support\Arr::random($post_ids),
        'body'=>$faker->paragraph,
        'parent_id'=>\Illuminate\Support\Arr::random(range(1,30))
    ];
});
