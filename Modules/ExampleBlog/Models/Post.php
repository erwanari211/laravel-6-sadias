<?php

namespace Modules\ExampleBlog\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\ExampleBlog\Presenters\PostPresenter;
use App\Traits\UniqueCodeTrait;

class Post extends Model
{
    use PostPresenter;
    use UniqueCodeTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'example_blog_posts';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [

        'author_id', 'unique_code', 'postable_type', 'postable_id',
        'title', 'slug', 'content',
        'status',

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
    public function author()
    {
        return $this->belongsTo('App\User', 'author_id');
    }

    public function postable()
    {
        return $this->morphTo();
    }

    public function tags()
    {
        return $this->belongsToMany('Modules\ExampleBlog\Models\Tag', 'example_blog_post_tag');
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
