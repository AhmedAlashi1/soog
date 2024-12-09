<?php

use App\Http\Controllers\AdvertisementsController;
use App\Http\Controllers\ProductsDetailsController;
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
    Route::controller(AdvertisementsController::class)->group(function () {
        Route::get('advertisements', 'index')->name('advertisements');

        Route::get('advertisements/get', 'get_prodect')->name('get_advertisements');

        Route::get('advertisements/create' , 'create')->name('advertisements.create');
        Route::post('advertisements/store' , 'store')->name('advertisements.store');

        Route::get('advertisements/edit/{id}' , 'edit')->name('advertisements.edit');
        Route::post('advertisements/update' , 'update')->name('advertisements.update');


        Route::post('advertisements/add' , 'add_prodect')->name('add_advertisements');

        Route::get('advertisements/{id}', 'index_show')->name('advertisements.show');

        Route::get('advertisements/show/{id}', 'show')->name('get_show');


        Route::post('advertisements/sortable', 'update_sort_order')->name('advertisements_sortable');


        Route::delete('advertisements/delete/{id}' , 'delete')->name('advertisements.delete');

        Route::get('exportOrders/advertisements', 'export')->name('advertisements.export');
    });
});
