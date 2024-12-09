<?php

use App\Http\Controllers\timesController;
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
    Route::controller(timesController::class)->group(function () {
        Route::get('times', 'Times')->name('times');

        Route::get('times/get', 'get_times')->name('get_times');

        Route::post('times/add' , 'add_times')->name('add_times');

        Route::get('times/edit/{id}' , 'edit')->name('times.edit');

        Route::post('times/update/{id}' , 'update')->name('times.update');

        Route::delete('times/delete/{id}' , 'delete')->name('times.delete');
    });
});