<?php

namespace App\Http\Resources\SubscriptionPlan;

use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionPlanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'                      => $this->id,
            'name'                    => $this->name,
            'num_of_installments'     => $this->num_of_installments,
            'installment_amount'      => $this->installment_amount,
            'installment_period_days' => $this->installment_period_days,
            'period_label'            => $this->period_label,
            'status'                  => config('params.subscriptionPlan.status')[$this->status],
            'created_at'              => date('Y-m-d H:i:s', strtotime($this->created_at)),
            'updated_at'              => date('Y-m-d H:i:s', strtotime($this->updated_at))
        ];
    }
}
