<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Topic;
use App\Models\Article;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArticleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Article::class;

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
            'topic_id' => function () {
                return Topic::all()->random();
            },
            'title' => $this->faker->sentence($nbWords = 7, $variableNbWords = true),
            'body' => $this->faker->paragraph($nbSentences = 15, $variableNbSentences = true),
            'status' => $this->faker->randomElement([
                Article::STATUS_ACTIVE,
                Article::STATUS_INACTIVE,
                Article::STATUS_PENDING,
                Article::STATUS_REJECTED
            ])
        ];
    }
}
