<?php

namespace Database\Factories;

use App\Like;
use App\Post;
use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class LikeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Like::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $user_ids = User::pluck('id')->toArray();
        $post_ids = Post::pluck('id')->toArray();
        return [
            'user_id'=>Arr::random($user_ids),
            'post_id'=>Arr::random($post_ids),
            'liked_at'=>now(),
        ];
    }
}
