<?php

use App\Http\Controllers\UsersAndAdminController;
use Illuminate\Support\Facades\Auth;
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
        // 'prefix' => "dashbord",
        'middleware' => ['auth']
] , function (){
    Route::controller(UsersAndAdminController::class)->group(function () {
        Route::get('clients', 'client')->name('client');

        Route::get('clients/get', 'get_clients')->name('get_clients');

        Route::post('client/add' , 'add_client')->name('add_client');

        Route::get('client/edit/{id}' , 'client_edit')->name('client.edit');

        Route::post('client/update/{id}' , 'client_update')->name('client.update');

        Route::delete('client/delete/{id}' , 'client_delete')->name('client.delete');
    });
});

