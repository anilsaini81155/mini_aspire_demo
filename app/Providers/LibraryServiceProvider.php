<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class LibraryServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        
    }


    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Contracts\AuthCheck',  'Library\AuthCheckLib');
    }

}
