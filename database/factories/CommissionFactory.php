<?php

namespace Database\Factories;

use App\Models\Commission;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommissionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Commission::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'clap_count' => rand(1, 15),
            'amount' => rand(100, 500),
            'status' => $this->faker->randomElement([Commission::STATUS_ACTIVE, Commission::STATUS_INACTIVE])
        ];
    }
}
