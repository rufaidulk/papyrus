<?php

namespace App\Services;

use App\Models\CompanyEwallet;
use App\Models\Ewallet;
use Exception;
use App\Models\Transaction;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\SubscriptionInstallment;

/**
 * Subscription Service
 */
class SubscriptionService
{
    public $subscription;
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    
    public function handle()
    {
        $subscriptionPlan = SubscriptionPlan::find($this->request->subscription_plan_id);

        DB::beginTransaction();

        try
        {
            $this->createSubscription($subscriptionPlan);
            $this->saveSubscriptionInstallments($subscriptionPlan);
            $this->initPayment();

            DB::commit();
        }
        catch (Exception $ex) {
            DB::rollBack();
            throw $ex;
        }

    }

    private function createSubscription(SubscriptionPlan $subscriptionPlan)
    {
        $this->subscription = new Subscription();
        $this->subscription->user_id = $this->request->user_id;
        $this->subscription->subscription_plan_id = $this->request->subscription_plan_id;
        $this->subscription->expiry_date = $this->calculateExpiryDate($subscriptionPlan);
        $this->subscription->status = Subscription::STATUS_ACTIVE;
        
        $this->subscription->save();
    }

    private function calculateExpiryDate(SubscriptionPlan $subscriptionPlan)
    {
        $days = $subscriptionPlan->num_of_installments * $subscriptionPlan->installment_period_days;

        return date('Y-m-d', strtotime("+ {$days} days"));
    }
    
    private function saveSubscriptionInstallments(SubscriptionPlan $subscriptionPlan)
    {

        $data = [];
        for ($i = 1; $i <= $subscriptionPlan->num_of_installments; $i++)
        {
            $data[] = [
                'user_id' => $this->subscription->user_id,
                'subscription_id' => $this->subscription->id,
                'amount' => $subscriptionPlan->installment_amount,
                'due_date' => $this->calculateInstallmentDueDate($i, $subscriptionPlan),
                'status' => SubscriptionInstallment::STATUS_PENDING
            ];
        }

        if (! empty($data)) {
            DB::table('subscription_installments')->insert($data);
        }
    }

    private function calculateInstallmentDueDate($installmentCount, SubscriptionPlan $subscriptionPlan)
    {
        $days = $installmentCount * $subscriptionPlan->installment_period_days;

        return date('Y-m-d', strtotime("+ {$days} days"));
    }

    private function initPayment()
    {
        $subscriptionInstallment = SubscriptionInstallment::where('subscription_id', $this->subscription->id)
            ->where('user_id', $this->request->user_id)
            ->where('status', SubscriptionInstallment::STATUS_PENDING)
            ->orderBy('due_date', 'asc')
            ->first();

        $txnId = $this->createTransaction($subscriptionInstallment);
        $this->updateSubscriptionInstallmentStatus($subscriptionInstallment, $txnId);
        $this->debitInstallmentAmtFromUserEwallet($subscriptionInstallment, $txnId);
        $this->creditInstallmentAmtToCompanyEwallet($subscriptionInstallment, $txnId);
    }

    private function createTransaction(SubscriptionInstallment $subscriptionInstallment)
    {
        $txn = new Transaction();
        $txn->ref_number = generate_ref_number();
        $txn->user_id = Auth::id();
        $txn->amount = $subscriptionInstallment->amount;
        $txn->description = 'Subscription installment Paid';
        $txn->txn_category = Transaction::TXN_SUBSCRIPTION;
        $txn->status = Transaction::STATUS_COMPLETED;
        $txn->save();

        return $txn->id;
    }

    private function updateSubscriptionInstallmentStatus(SubscriptionInstallment $subscriptionInstallment, $txnId)
    {
        $subscriptionInstallment->transaction_id = $txnId;
        $subscriptionInstallment->paid_date = date('Y-m-d H:i:s');
        $subscriptionInstallment->status = SubscriptionInstallment::STATUS_PAID;

        $subscriptionInstallment->save();
    }

    private function debitInstallmentAmtFromUserEwallet(SubscriptionInstallment $subscriptionInstallment, $txnId)
    {
        $wallet = new Ewallet();
        $wallet->user_id = $this->request->user_id;
        $wallet->transaction_id = $txnId;
        $wallet->transaction_type = Ewallet::TYPE_DEBIT;
        $wallet->amount = $subscriptionInstallment->amount;

        $wallet->save();
    }

    private function creditInstallmentAmtToCompanyEwallet(SubscriptionInstallment $subscriptionInstallment, $txnId)
    {
        $wallet = new CompanyEwallet();
        $wallet->transaction_id = $txnId;
        $wallet->transaction_type = CompanyEwallet::TYPE_CREDIT;
        $wallet->amount = $subscriptionInstallment->amount;

        $wallet->save();
    }
}