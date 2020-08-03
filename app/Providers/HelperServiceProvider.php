<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Tools_model;
use View;

class HelperServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        foreach (glob(app_path() . '/Helpers/*.php') as $file) 
        {
            require_once($file);
        }
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('admin.include.sidebar', function ($view) {
            
        });
    }
}
