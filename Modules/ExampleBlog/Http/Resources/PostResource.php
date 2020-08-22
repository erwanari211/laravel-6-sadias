<?php

namespace Modules\ExampleBlog\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class PostResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [

            'author_id' => $this->author_id,
            'unique_code' => $this->unique_code,
            'postable' => $this->postable,
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            'status' => $this->status,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,

        ];
    }
}
