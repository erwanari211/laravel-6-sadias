<?php

namespace Modules\ExampleBlog\Entities;

use Illuminate\Database\Eloquent\Model;

class ExampleBlogChannel extends Model
{
    protected $fillable = [
        'owner_id', 'name', 'slug', 'description', 'is_active',
    ];

    public function owner()
    {
        return $this->belongsTo('App\User', 'owner_id');
    }
}
