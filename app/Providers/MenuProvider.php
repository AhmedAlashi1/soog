<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Categories as Category;

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
        $this->categories = Category::orderBy('sort_order')->where('parent_id', '0')->get();
        view()->composer('Front.LandingPage.Menu', function($view) {
            $view->with(['categories' => $this->categories]);
        });
    }
}
