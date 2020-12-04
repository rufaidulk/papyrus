<?php

namespace App\Http\Resources\Subscription;

use App\Models\Subscription;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SubscriptionCollection extends ResourceCollection
{
    /**
    * The "data" wrapper that should be applied.
    *
    * @var string
    */
    public static $wrap = 'subscriptions';

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'subscriptions' => $this->collection->transform(function(Subscription $subscription) {
                return (new SubscriptionResource($subscription));
            }),
        ];
    }
}
