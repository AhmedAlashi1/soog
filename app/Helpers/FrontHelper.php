<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Route;


trait FrontHelper
{
    public function nav_active($name)
    {
        if (Route::current()->getName() == $name) {
            return "active";
        } else {
            return "";
        }
    }
}
