<?php

// use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\DeliveriesController;
use App\Http\Controllers\DeliveryController;
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
    Route::controller(DeliveryController::class)->group(function () {
        Route::get('delivery', 'delivery')->name('delivery');

        Route::get('delivery/get', 'get_delivery')->name('get_delivery');

        Route::post('delivery/add' , 'add_delivery')->name('add_delivery');

        Route::get('delivery/edit/{id}' , 'edit')->name('delivery.edit');

        Route::post('delivery/update/{id}' , 'update')->name('delivery.update');

        Route::delete('delivery/delete/{id}' , 'delete')->name('delivery.delete');
    });
});

