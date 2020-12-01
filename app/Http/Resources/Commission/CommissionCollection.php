<?php

namespace App\Http\Resources\Commission;

use App\Models\Commission;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CommissionCollection extends ResourceCollection
{
    /**
    * The "data" wrapper that should be applied.
    *
    * @var string
    */
    public static $wrap = 'commissions';

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'commissions' => $this->collection->transform(function(Commission $commission) {
                return (new CommissionResource($commission));
            }),
        ];
    }
}
