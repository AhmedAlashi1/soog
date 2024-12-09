<?php

use App\Http\Controllers\CouponsController;
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
    Route::controller(CouponsController::class)->group(function () {
        Route::get('coupons', 'index')->name('coupons');

        Route::get('coupons/get', 'get_coupons')->name('get_coupons');

        Route::post('coupons/add' , 'add_coupons')->name('add_coupons');

        Route::get('coupons/edit/{id}' , 'edit')->name('coupons.edit');

        Route::post('coupons/update/{id}' , 'update')->name('coupons.update');

        Route::delete('coupons/delete/{id}' , 'delete')->name('category.delete');
    });
});

