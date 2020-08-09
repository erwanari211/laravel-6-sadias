<?php

namespace Modules\ExampleBlog\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\ExampleBlog\Presenters\TeamMemberPresenter;

class TeamMember extends Model
{
    use TeamMemberPresenter;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'example_blog_team_members';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [

        'user_id', 'team_id', 'role_name',
        'is_active',

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
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function team()
    {
        return $this->belongsTo('Modules\ExampleBlog\Models\Team', 'team_id');
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
