<?php

namespace Modules\ExamplePermission\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class RoleResource extends Resource
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
            'id' => $this->id,

            'name' => $this->name,
            'guard_name' => $this->guard_name,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,

        ];
    }
}
