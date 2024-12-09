<?php

use App\Http\Controllers\DeliveryTypesController;
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
    Route::controller(DeliveryTypesController::class)->group(function () {
        Route::get('deliveryTypes', 'deliveryTypes')->name('deliveryTypes');

        Route::get('deliveryTypes/get', 'get_deliveryTypes')->name('get_deliveryTypes');

        Route::post('deliveryTypes/add' , 'add_deliveryTypes')->name('add_deliveryTypes');

        Route::get('deliveryTypes/edit/{id}' , 'edit')->name('deliveryTypes.edit');

        Route::post('deliveryTypes/update/{id}' , 'update')->name('deliveryTypes.update');

        Route::delete('deliveryTypes/delete/{id}' , 'delete')->name('deliveryTypes.delete');
    });
});