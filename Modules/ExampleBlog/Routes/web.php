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

Route::group(['prefix' => 'example-blog', 'as' => 'example.blog.'], function () {
    Route::group(['prefix' => 'backend', 'as' => 'backend.', 'namespace' => 'Backend'], function () {
        Route::resource('channels', 'ChannelController')->middleware('auth');
    });

    Route::get('/', 'ExampleBlogController@index');
});

Route::group(['prefix' => 'example-blog', 'as' => 'example-blog.'], function () {
    Route::resource('posts', 'PostController')->middleware('auth');
    Route::resource('comments', 'CommentController')->middleware('auth');
    Route::resource('teams', 'TeamController')->middleware('auth');
    Route::resource('team-members', 'TeamMemberController')->middleware('auth');
});
