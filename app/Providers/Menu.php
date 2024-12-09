<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class MenuProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */

    public $categories;

    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->categories = ["Home", "About Us", "Contact"];

        view()->composer('layouts.master', function($view) {
            $view->with(['categories' => $this->categories]);
        });
    }
}
