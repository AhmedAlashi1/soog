<?php

use App\Http\Controllers\CategoriesController;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale()."/admin",
        // 'prefix' => "admin",
        'middleware' => ['auth']
] , function (){
    Route::controller(CategoriesController::class)->group(function () {
        Route::get('category', 'category')->name('category');

        Route::get('categories/show/{id}', 'show')->name('category.show');

        Route::get('categories/get', 'get_categories')->name('get_categories');

        Route::post('categories/sortable', 'update_sort_order')->name('categories_sortable');

        Route::get('category/show/{id}', 'show_categories')->name('show_categories');

        Route::post('category/add' , 'add_category')->name('add_category');

        Route::get('category/edit/{id}' , 'edit')->name('category.edit');

        Route::post('category/update/{id}' , 'update')->name('category.update');

       Route::delete('category/delete/{id}' , 'delete')->name('category.delete');
    });
});

