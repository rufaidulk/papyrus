<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Transaction::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'ref_number' => 'TR-' . $this->faker->randomNumber($nbDigits = 6),
            'user_id' => function () {
                return User::all()->random();
            },
            'amount' => rand(100, 1500),
            'description' => $this->faker->sentence,
            'txn_category' => $this->faker->randomElement([
                Transaction::TXN_COMMISSION, Transaction::TXN_SUBSCRIPTION
            ]),
            'status' => Transaction::STATUS_COMPLETED
        ];
    }
}
