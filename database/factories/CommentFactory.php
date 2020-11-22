<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Factories\Factory;

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
        return [
            'user_id' => function () {
                return User::all()->random();
            },
            'article_id' => function () {
                return Article::all()->random();
            },
            'body' => $this->faker->text($maxNbChars = 200),
            'status' => $this->faker->randomElement([
                Comment::STATUS_ACTIVE,
                Comment::STATUS_INACTIVE,
            ])
        ];
    }
}
