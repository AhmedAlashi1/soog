<?php

use App\Http\Controllers\ContactsController;
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
    Route::controller(ContactsController::class)->group(function () {
        Route::get('contact', 'contact')->name('contact');

        Route::get('contact/get', 'get_contacts')->name('get_contact');

        // Route::post('category/add' , 'add_category')->name('add_category');

        // Route::get('category/edit/{id}' , 'edit')->name('category.edit');

        // Route::post('category/update/{id}' , 'update')->name('category.update');

        Route::delete('contact/delete/{id}' , 'delete')->name('contact.delete');
    });
});

