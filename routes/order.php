<?php

use App\Http\Controllers\OedersController;
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
    Route::controller(OedersController::class)->group(function () {
        Route::get('orders', 'orders')->name('orders');

        Route::get('orders/get', 'get_orders')->name('get_orders');

        Route::get('orders/flters', 'flters')->name('flters');

        // Route::post('category/add' , 'add_category')->name('add_category');

        // Route::get('category/edit/{id}' , 'edit')->name('category.edit');

        // Route::post('category/update/{id}' , 'update')->name('category.update');

        Route::delete('orders/delete/{id}' , 'delete')->name('orders.delete');

        Route::get('orders/appUser', 'export')->name('orders.export');

        Route::get('DownloadOrderPDF/{id}', 'DownloadOrderPDF')->name('DownloadOrderPDF');

    });
});
