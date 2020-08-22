<?php

namespace Modules\ExampleBlog\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\ExampleBlog\Presenters\TagPresenter;

class Tag extends Model
{
    use TagPresenter;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'example_blog_tags';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [

        'owner_id', 'name', 'slug',
        'description', 'is_active',
        'ownerable_id', 'ownerable_type',

    ];
    // protected $hidden = [];
    // protected $with = [];
    // protected $perPage = 15;
    // protected $casts = [];
    // protected $dates = [];
    // protected $appends = [];

    public static $validationRules = [];

    public static $validationAttributes = [];

    public static $validationMessages = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function owner()
    {
        return $this->belongsTo('App\User', 'owner_id');
    }

    public function ownerable()
    {
        return $this->morphTo();
    }

    public function posts()
    {
        return $this->belongsToMany('Modules\ExampleBlog\Models\Post', 'example_blog_post_tag');
    }


    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
