<?php

namespace Database\Factories;

use App\Models\Ewallet;
use App\Models\Subscription;
use App\Models\SubscriptionInstallment;
use App\Models\SubscriptionPlan;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriptionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Subscription::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $subscriptionPlan = SubscriptionPlan::all()->random();
        $txnId = $this->createTransaction();
        return [
            'user_id' => function () {
                return User::all()->random();
            },
            'subscription_plan_id' => $subscriptionPlan->id,
            'transaction_id' => $txnId,
            'expiry_date' => $this->calculateExpiryDate($subscriptionPlan),
            'status' => $this->faker->randomElement([
                Subscription::STATUS_ACTIVE, Subscription::STATUS_INACTIVE, Subscription::STATUS_EXPIRED
            ])
        ];
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (Subscription $subscription) {
            $this->createSubscriptionInstallments($subscription);
            $this->createEwallet($subscription);
        });
    }

    private function createTransaction()
    {
        $txn = Transaction::factory()->create([
            'ref_number' => 'TR-' . $this->faker->randomNumber($nbDigits = 6),
            'user_id' => function () {
                return User::all()->random();
            },
            'amount' => rand(100, 1500),
            'description' => $this->faker->sentence,
            'txn_category' => Transaction::TXN_SUBSCRIPTION,
            'status' => Transaction::STATUS_COMPLETED
        ]);
        
        return $txn->id;
    }

    private function calculateExpiryDate(SubscriptionPlan $subscriptionPlan)
    {
        $days = $subscriptionPlan->num_of_installments * $subscriptionPlan->installment_period_days;

        return date('Y-m-d', strtotime("+ {$days} days"));
    }

    private function createSubscriptionInstallments($subscription)
    {
        $subscriptionPlan = SubscriptionPlan::find($subscription->subscription_plan_id);

        $data = [];
        for ($i = 1; $i <= $subscriptionPlan->num_of_installments; $i++)
        {
            $data[] = [
                'user_id' => $subscription->user_id,
                'subscription_id' => $subscription->id,
                'amount' => $subscriptionPlan->installment_amount,
                'due_date' => $this->calculateInstallmentDueDate($i, $subscriptionPlan),
                'status' => SubscriptionInstallment::STATUS_PENDING
            ];
        }
    }

    private function calculateInstallmentDueDate($installmentCount, SubscriptionPlan $subscriptionPlan)
    {
        $days = $installmentCount * $subscriptionPlan->installment_period_days;

        return date('Y-m-d', strtotime("+ {$days} days"));
    }

    private function createEwallet(Subscription $subscription)
    {
        $subscriptionPlan = SubscriptionPlan::find($subscription->subscription_plan_id);

        Ewallet::factory()->create([
            'user_id' => $subscription->user_id,
            'transaction_id' => $subscription->transaction_id,
            'transaction_type' => Ewallet::TYPE_DEBIT,
            'amount' => $subscriptionPlan->installment_amount * $subscriptionPlan->num_of_installments
        ]);
    }
}
