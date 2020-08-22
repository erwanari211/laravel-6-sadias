<?php

namespace Modules\ExampleBlog\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class TagResource extends Resource
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

            'owner_id' => $this->owner_id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'is_active' => $this->is_active,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,

        ];
    }
}
