<?php

namespace App\Http\Resources\Artilcle;

use App\Models\Article;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ArticleCollection extends ResourceCollection
{
    /**
    * The "data" wrapper that should be applied.
    *
    * @var string
    */
    public static $wrap = 'articles';

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'articles' => $this->collection->transform(function(Article $article) {
                return (new ArticleResource($article));
            }),
        ];
    }
}
