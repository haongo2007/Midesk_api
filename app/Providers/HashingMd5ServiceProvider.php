<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\MyClass\MD5Hasher;

class HashingMd5ServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    public function boot()
    {
        app('hash')->extend('md5',  function () {
            return new MD5Hasher();
        });
    }
}
