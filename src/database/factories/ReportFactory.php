<?php

namespace Database\Factories;

use App\Report;
use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use \Illuminate\Support\Arr;

class ReportFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Report::class;

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
            'post_id'=>1,
            'reason'=>$this->faker->word,
        ];
    }
}
