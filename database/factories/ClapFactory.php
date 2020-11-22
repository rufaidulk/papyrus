<?php

namespace Database\Factories;

use App\Models\Clap;
use App\Models\User;
use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClapFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Clap::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => function () {
                return User::all()->random();
            },
            'article_id' => function () {
                return Article::all()->random();
            },
            'clap_count' => rand(1, 15),
            'date' => date('Y-m-d', strtotime("+" . rand(1, 60) . " days"))
        ];
    }
}
