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


Route::group(['prefix'=>'/app/v1'], function(){

    //不需要登录验证的接口
//    Route::get('')

    Route::get('/', function(){
        return view('welcome');
    });
});

Auth::check();
