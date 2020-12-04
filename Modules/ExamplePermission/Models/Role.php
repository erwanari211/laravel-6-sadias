<?php

namespace Modules\ExamplePermission\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\ExamplePermission\Presenters\RolePresenter;

class Role extends Model
{
    use RolePresenter;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    // protected $table = 'table';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [

        'name', 'guard_name', 

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
