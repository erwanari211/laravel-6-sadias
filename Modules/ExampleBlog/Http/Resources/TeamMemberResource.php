<?php

namespace Modules\ExampleBlog\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class TeamMemberResource extends Resource
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

            'user_id' => $this->user_id,
            'team_id' => $this->team_id,
            'role_name' => $this->role_name,
            'is_active' => $this->is_active,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,

        ];
    }
}
