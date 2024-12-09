<?php

use App\Http\Controllers\AdsController;
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
    Route::controller(AdsController::class)->group(function () {
        Route::get('ads', 'ads')->name('ads');

        Route::get('ads/get', 'get_ads')->name('get_ads');
        Route::get('ads/create', 'create')->name('ads.create');

        Route::post('ads/add' , 'add_ads')->name('add_ads');

        Route::get('ads/edit/{id}' , 'edit')->name('ads.edit');

        Route::post('ads/update/{id}' , 'update')->name('ads.update');

        Route::delete('ads/delete/{id}' , 'delete')->name('ads.delete');
    });
});

