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
    Route::resource('roles', 'RoleController')->middleware('auth');

    Route::get('permissions/datatables', 'Datatables\PermissionController@index')->middleware('auth')->name('permissions.datatables.index');
    Route::resource('permissions', 'PermissionController')->middleware('auth');

    Route::get('users/datatables', 'Datatables\UserController@index')->middleware('auth')->name('users.datatables.index');
    Route::resource('users', 'UserController')->middleware('auth');
});
