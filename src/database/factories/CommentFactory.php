<?php

namespace Database\Factories;

use App\Comment;
use App\Post;
use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class CommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Comment::class;

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
            'body'=>$this->faker->paragraph,
            'parent_id'=>Arr::random(range(1,30))
        ];
    }
}
