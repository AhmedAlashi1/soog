<?php

namespace App\Http\Controllers;

use App\Models\Categories as Category;
use Illuminate\Support\ServiceProvider;

class MenuProvider extends ServiceProvider
{
    public $categories;
    public function MenuShow() {
        $this->categories = Category::with('sub')->get();
        view()->composer('Front.LandingPage.Menu', function($view) {
            $view->with(['categories' => $this->categories]);
        });
    }
}
