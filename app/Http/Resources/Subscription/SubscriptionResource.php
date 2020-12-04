<?php

namespace App\Http\Resources\Subscription;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\SubscriptionPlan\SubscriptionPlanResource;

class SubscriptionResource extends JsonResource
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
            'id'         => $this->id,
            'user'       => $this->user,
            'subscriptionPlan' => (new SubscriptionPlanResource($this->subscriptionPlan)),
            'expiry_date' => date('Y-m-d H:i:s', strtotime($this->expiry_date)),
            'status'     => config('params.subscription.status')[$this->status],
            'created_at' => date('Y-m-d H:i:s', strtotime($this->created_at)),
            'updated_at' => date('Y-m-d H:i:s', strtotime($this->updated_at))
        ];
    }
}
