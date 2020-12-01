<?php

namespace App\Http\Resources\SubscriptionPlan;

use App\Models\SubscriptionPlan;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SubscriptionPlanCollection extends ResourceCollection
{
    /**
    * The "data" wrapper that should be applied.
    *
    * @var string
    */
    public static $wrap = 'subscriptionPlans';

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'subscriptionPlans' => $this->collection->transform(function(SubscriptionPlan $subcriptionPlan) {
                return (new SubscriptionPlanResource($subscriptionPlan));
            }),
        ];
    }
}
