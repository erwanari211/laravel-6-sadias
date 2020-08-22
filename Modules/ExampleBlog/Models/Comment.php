<?php

namespace Modules\ExampleBlog\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\ExampleBlog\Presenters\CommentPresenter;

class Comment extends Model
{
    use CommentPresenter;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'example_blog_comments';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [

        'author_id', 'post_id', 'parent_id',
        'content', 'is_approved', 'status',

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

    public function post()
    {
        return $this->belongsTo('Modules\ExampleBlog\Models\Post');
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
