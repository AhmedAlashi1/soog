<?php

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
    Route::controller(\App\Http\Controllers\ArchivesController::class)->group(function () {
        Route::get('archives', 'index')->name('archives');

        Route::get('archives/get', 'get_archives')->name('get_archives');

        Route::delete('archives/delete/{id}' , 'delete')->name('archives.delete');
    });
});

