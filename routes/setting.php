<?php

use App\Http\Controllers\SettingController;
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
],  function () {
        Route::get('setting/global' , [SettingController::class , 'index'])->name('setting.global');
        Route::get('setting/social' , [SettingController::class , 'social'])->name('setting.social');
        Route::post('update/setting/global' , [SettingController::class , 'update'])->name('setting.update');
});
