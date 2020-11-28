<?php

namespace App\Http\Resources\Subject;

use App\Models\Subject;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SubjectCollection extends ResourceCollection
{ 
    /**
    * The "data" wrapper that should be applied.
    *
    * @var string
    */
    public static $wrap = 'subjects';

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'subjects' => $this->collection->transform(function(Subject $subject) {
                return (new SubjectResource($subject));
            }),
        ];
    }
}
