<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('pages.index');
});


// Blog Route Group
Route::group(['prefix' => 'blog'], function () {
    Route::get('/', [
        'as'    => 'blog',
        'uses'  => 'BlogController@index'
    ]);

    Route::get('/{slug}', [
        'as'    => 'blog.show',
        'uses'  => 'BlogController@showPost'
    ]);
});


// Redirect to Admin area
Route::get('admin', function () {
    return redirect('/admin/post');
});


// Admin Area Routes
Route::group([
        'prefix'        => 'admin',
        'namespace'     => 'Admin',
        'middleware'    => 'auth'
], function () {
        Route::resource('/post',        'PostController', ['except' => 'show']);
        Route::resource('/tag',         'TagController', ['except' => 'show']);
        Route::get('/upload',           'UploadController@index');
        Route::post('/upload/file',     'UploadController@uploadFile');
        Route::delete('/upload/file',   'UploadController@deleteFile');
        Route::post('/upload/folder',   'UploadController@createFolder');
        Route::delete('/upload/folder', 'UploadController@deleteFolder');
});


// Authentication Route group
Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function () {
    Route::get('/login', 'AuthController@getLogin');
    Route::post('/login', 'AuthController@postLogin');
    Route::get('/logout', 'AuthController@getLogout');
});