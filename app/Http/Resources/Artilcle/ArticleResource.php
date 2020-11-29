<?php

namespace App\Http\Resources\Artilcle;

use App\Http\Resources\Topic\TopicResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
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
            'title'      => $this->title,
            'body'       => $this->body,
            'user'       => [
                'id'   => $this->user->id,
                'name' => $this->user->name
            ],
            'topic'      => (new TopicResource($this->topic)),
            'tags'       => $this->tags,
            'status'     => config('params.article.status')[$this->status],
            'created_at' => date('Y-m-d H:i:s', strtotime($this->created_at)),
            'updated_at' => date('Y-m-d H:i:s', strtotime($this->updated_at))
        ];
    }
}
