<?php

namespace Modules\ExampleBlog\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class CommentResource extends Resource
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
            'post_id' => $this->post_id,
            'parent_id' => $this->parent_id,
            'content' => $this->content,
            'is_approved' => $this->is_approved,
            'status' => $this->status,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,

        ];
    }
}
