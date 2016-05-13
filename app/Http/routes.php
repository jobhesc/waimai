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

    Route::match(['get', 'post'], '/get_shops', 'ShopsController@get_shops');

    Route::match(['get', 'post'], '/', function(){
        return App\User::paginate();
    });

    //查询店铺分类
    Route::match(['get', 'post'], '/get_categories', 'ShopCategoriesController@get_categories');
});

