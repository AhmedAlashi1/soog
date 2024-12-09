<?php

use App\Http\Controllers\Payment_methodsController;
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
    Route::controller(Payment_methodsController::class)->group(function () {
        Route::get('payment', 'payment')->name('payment');

        Route::get('payments/get', 'get_payments')->name('get_payments');

        Route::post('payment/add' , 'add_payment')->name('add_payment');

        Route::get('payment/edit/{id}' , 'edit')->name('payment.edit');

        Route::post('payment/update/{id}' , 'update')->name('payment.update');

        Route::delete('payment/delete/{id}' , 'delete')->name('payment.delete');
    });
});

