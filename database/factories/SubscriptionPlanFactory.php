<?php

namespace Database\Factories;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriptionPlanFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SubscriptionPlan::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $periodDays = $this->faker->randomElement([7, 14, 30, 60, 90, 180, 365]);
        
        return [
            'name' => $this->faker->unique()->word,
            'num_of_installments' => rand(1, 15),
            'installment_amount' => rand(100, 1500),
            'installment_period_days' => $periodDays,
            'period_label' => $this->getPeriodLabel($periodDays),
            'status' => $this->faker->randomElement([
                SubscriptionPlan::STATUS_ACTIVE,
                SubscriptionPlan::STATUS_INACTIVE,
            ])
        ];
    }

    private function getPeriodLabel($periodDays)
    {
        $labels = [
            7 => 'weekly', 
            14 => 'fortnight', 
            30 => 'monthly', 
            60 => 'bimonthly', 
            90 => 'trimonthly', 
            180 => 'half yearly', 
            365 => 'yearly'
        ];

        return $labels[$periodDays];
    }
}
