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
        'middleware' => ['auth']
] , function (){
    Route::controller(UsersAndAdminController::class)->group(function () {
        Route::get('admins', 'admin')->name('admin');

        Route::get('admins/get', 'get_admins')->name('get_admins');

        Route::post('admin/add' , 'add_admin')->name('add_admin');

        Route::get('admin/edit/{id}' , 'edit')->name('admin.edit');

        Route::post('admin/update/{id}' , 'update')->name('admin.update');

        Route::delete('admin/delete/{id}' , 'delete')->name('admin.delete');
    });
});

