<?php

use App\Http\Controllers\Roles\RolesController;
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
],  function () {
        Route::resource( 'roles' , RolesController::class);
        Route::get('show/{id}/{user_id}' , [RolesController::class , 'edit_role'])->name('edit_role');
        Route::post('update/r/{id}' , [RolesController::class , 'update'])->name('edit_role');
        Route::get('get_roles' , [RolesController::class , 'get_roles'])->name('get_roles');
        Route::delete('destroy/{id}/{user_id}' , [RolesController::class , 'destroy'])->name('destroy');
});
