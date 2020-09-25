<?php

namespace Database\Factories;

use App\View;
use Illuminate\Database\Eloquent\Factories\Factory;

class ViewFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = View::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $user_ids = \App\User::pluck('id')->toArray();
        $post_ids = \App\Post::pluck('id')->toArray();
        return [
            'user_id'=>\Illuminate\Support\Arr::random($user_ids),
            'post_id'=>\Illuminate\Support\Arr::random($post_ids),
            'viewed_at'=>now()
        ];
    }
}
