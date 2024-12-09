<?php

use App\Http\Controllers\CitiesController;
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
    Route::controller(CitiesController::class)->group(function () {
        Route::get('cities', 'cities')->name('cities');

        Route::get('cities/get', 'get_cities')->name('get_cities');

        Route::post('cities/add' , 'add_Cities')->name('add_cities');

        Route::get('city/edit/{id}' , 'edit')->name('city.edit');

        Route::post('city/update/{id}' , 'update')->name('city.update');

        Route::delete('city/delete/{id}' , 'delete')->name('city.delete');

        Route::get('city/{id}', 'index_show')->name('city.show');

        Route::get('city/show/{id}', 'show')->name('get_show_city');
    });
});

