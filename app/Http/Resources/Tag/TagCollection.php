<?php

namespace App\Http\Resources\Tag;

use App\Models\Tag;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TagCollection extends ResourceCollection
{
    /**
    * The "data" wrapper that should be applied.
    *
    * @var string
    */
    public static $wrap = 'tags';

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'tags' => $this->collection->transform(function(Tag $tag) {
                return (new TagResource($tag));
            }),
        ];
    }
}
