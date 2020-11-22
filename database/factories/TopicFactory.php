<?php

namespace Database\Factories;

use App\Models\Subject;
use App\Models\Topic;
use Illuminate\Database\Eloquent\Factories\Factory;

class TopicFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Topic::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'subject_id' => function () {
                return Subject::all()->random();
            },
            'name' => $this->faker->unique()->word,
            'status' => $this->faker->randomElement([Topic::STATUS_ACTIVE, Topic::STATUS_INACTIVE])
        ];
    }
}
