<?php

namespace Database\Factories;

use App\Post;
use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $user_ids = User::pluck('id')->toArray();
        return [
            'user_id'=>Arr::random($user_ids),
            'url'=>'/2020/default.png',
            'description'=>$this->faker->paragraph,
            'show_num_of_likes_to_all'=>$this->faker->boolean(80),
            'just_for_creator'=>$this->faker->boolean(20),
        ];
    }
}
