<?php

use App\Http\Controllers\GovernoratesController;
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
    Route::controller(GovernoratesController::class)->group(function () {
        Route::get('countries', 'governorates')->name('countries');

        Route::get('countries/get', 'get_governorates')->name('get_governorates');

        Route::post('countries/add' , 'add_governorat')->name('add_governorat');

        Route::get('countries/edit/{id}' , 'edit')->name('governorat.edit');

        Route::post('countries/update/{id}' , 'update')->name('governorat.update');

        Route::delete('countries/delete/{id}' , 'delete')->name('governorat.delete');
    });
});

