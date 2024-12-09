<?php

use App\Http\Controllers\AppUsersController;
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
    Route::controller(AppUsersController::class)->group(function () {
        Route::get('appUser', 'app_user')->name('app_user');

        Route::get('appUser/get', 'get_appUser')->name('get_appUser');

        Route::get('app_user_address/{id}', 'app_user_address_index')->name('app_user_address');

        Route::get('appUser/get_address/{id}', 'get_app_user_address')->name('get_address');

        Route::post('appUser/add_address/{id}/{type}' , 'add_app_user_address')->name('add_address');

        Route::delete('appUser/add_address/delete/{id}' , 'delete_app_user_address')->name('add_address.delete');

        // Route::get('category/edit/{id}' , 'edit')->name('category.edit');

        // Route::post('category/update/{id}' , 'update')->name('category.update');

        Route::delete('appUser/delete/{id}' , 'delete')->name('category.delete');

        Route::post('add/appUser' , 'add100')->name('ds');

        Route::get('exportOrders/appUser', 'export')->name('appUser.export');
    });
});

