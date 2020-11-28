<?php

namespace App\Http\Resources\Topic;

use App\Models\Topic;
use App\Http\Resources\Topic\TopicResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TopicCollection extends ResourceCollection
{
    /**
    * The "data" wrapper that should be applied.
    *
    * @var string
    */
    public static $wrap = 'topics';

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'topics' => $this->collection->transform(function(Topic $topic) {
                return (new TopicResource($topic));
            }),
        ];
    }
}
