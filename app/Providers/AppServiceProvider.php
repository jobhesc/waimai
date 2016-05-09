<?php

namespace App\Providers;

use App\Http\Validator\Signature;
use Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //签名验证规则
        Validator::extend('signature', 'App\Http\Validator\Signature@handle');
        //token验证规则
        Validator::extend('token', 'App\Http\Validator\Token@handle');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
