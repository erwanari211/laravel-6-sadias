<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('examplepermission')->group(function() {
    Route::get('/', 'ExamplePermissionController@index');
});


Route::group(['prefix' => 'example-permission', 'as' => 'example-permission.'], function () {
    Route::get('roles/datatables', 'Datatables\RoleController@index')->middleware('auth')->name('roles.datatables.index');
    Route::put('roles/{role}/permissions', 'RolePermissionController@update')->middleware('auth')->name('roles.permissions.update');
    Route::resource('roles', 'RoleController')->middleware('auth');

    Route::get('permissions/datatables', 'Datatables\PermissionController@index')->middleware('auth')->name('permissions.datatables.index');
    Route::resource('permissions', 'PermissionController')->middleware('auth');

    Route::get('users/datatables', 'Datatables\UserController@index')->middleware('auth')->name('users.datatables.index');
    Route::put('users/{user}/roles', 'UserRoleController@update')->middleware('auth')->name('users.roles.update');
    Route::resource('users', 'UserController')->middleware('auth');

    Route::get('pages', 'PageController@index')->middleware('auth')->name('pages.home');
    Route::get('pages/super-admin', 'PageController@superAdmin')->middleware('auth')->name('pages.super-admin');
    Route::get('pages/admin', 'PageController@admin')->middleware('auth')->name('pages.admin');
    Route::get('pages/normal', 'PageController@normal')->middleware('auth')->name('pages.normal');
    Route::get('pages/permission/{permission}', 'PageController@permission')->middleware('auth')->name('pages.permission.show');
});
