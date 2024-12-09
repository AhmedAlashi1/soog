<?php

use App\Http\Controllers\AdsController;
use App\Http\Controllers\AppUsersController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\CouponsController;
use App\Http\Controllers\ProdectController;
use App\Http\Controllers\CitiesController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\DeliveryTypesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OedersController;
use App\Http\Controllers\Payment_methodsController;
use App\Http\Controllers\ProductsDetailsController;
use App\Http\Controllers\timesController;
use App\Models\Country;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotifyController;
use App\Http\Controllers\GovernoratesController;
use App\Http\Controllers\AdvertisementsController;
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

use Illuminate\Support\Facades\Artisan;

Route::get('authorized/google/callback', [\App\Http\Controllers\Api\V1\UserController::class, 'handleGoogleCallback']);

Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('route:clear');

    return "Cache cleared successfully";
});
Route::get('/test' ,[OedersController::class , 'MULTIDownloadOrderPDF']);
Route::post('send', [NotifyController::class, 'sendNotification'])->name('Notification.send');




Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['auth'],
    ], function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('admin/best/selling/clothes', [ProductsDetailsController::class, 'index'])->name('mmm');
    Route::get('admin/clothes/gettt', [ProductsDetailsController::class, 'get_d'])->name('get_d');
    Route::get('admin/clothes/getm', [ProductsDetailsController::class, 'get_m'])->name('get_m');
    Route::get('admin/modern/selling/clothes', [ProductsDetailsController::class, 'indexm'])->name('aaa');
    Route::get('admin/appUser/notify2', [NotifyController::class, 'index'])->name('notify2');
    Route::get('admin/appUser/notify', [NotifyController::class, 'index2'])->name('notify');
    Route::get('admin/notification', [NotifyController::class, 'index3'])->name('notification');
    Route::get('admin/get_notification', [NotifyController::class, 'get_notification'])->name('get_notification');
    Route::post('notification/add', [NotifyController::class, 'add_notification'])->name('add_notification');
    Route::get('notification/edit/{id}', [NotifyController::class, 'edit'])->name('notification.edit');
    Route::post('notification/update/{id}', [NotifyController::class, 'update'])->name('notification.update');
    Route::delete('notification/delete/{id}', [NotifyController::class, 'delete'])->name('notification.delete');
    Route::get('admin/edit', 'App\Http\Controllers\UsersAndAdminController@edit_admin')->name('admin.edit');
    Route::post('admin/update', [App\Http\Controllers\UsersAndAdminController::class, 'update_admin'])->name('admin.updat');
    Route::get('admin/resetPassword', [App\Http\Controllers\UsersAndAdminController::class, 'reset_Password']);
    Route::post('admin/reset-Password', [App\Http\Controllers\UsersAndAdminController::class, 'resetPassword'])->name('admin.resetPassword');



});
Auth::routes();
// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('category/update/status', [CategoriesController::class, 'updateStatus'])->name('update.status');
Route::post('clothes/update/status', [AdvertisementsController::class, 'updateStatus'])->name('advertisements.status');

Route::post('coupon/update/status', [CouponsController::class, 'updateStatus'])->name('coupon.status');
Route::post('ads/update/status', [AdsController::class, 'updateStatus'])->name('ads.status');
Route::post('city/update/status', [CitiesController::class, 'updateStatus'])->name('city.status');
Route::post('governorate/update/status', [GovernoratesController::class, 'updateStatus'])->name('governorate.status');
Route::post('appuser/update/status', [AppUsersController::class, 'updateStatus'])->name('appuser.status');
Route::post('payment/update/status', [Payment_methodsController::class, 'updateStatus'])->name('payments.status');
Route::post('deliveryTypes/update/status', [DeliveryTypesController::class, 'updateStatus'])->name('deliveryTypes.status');
Route::post('time/update/status', [timesController::class, 'updateStatus'])->name('time.status');
Route::post('delivery/update/status', [DeliveryController::class, 'updateStatus'])->name('delivery.status');
Route::post('notification/statuss', [NotifyController::class, 'updateStatus']);

Route::post('country/update/status', [\App\Http\Controllers\CountryController::class, 'updateStatus'])->name('country.status');

Route::post('order/update/status', [OedersController::class, 'updateStatus'])->name('order.status');
Route::post('order2/update/status', [OedersController::class, 'updateStatus'])->name('order2.status');
Route::post('order3/update/status', [OedersController::class, 'updateStatus'])->name('order3.status');

Route::delete('orders/delete/all', [OedersController::class, 'ordersAll'])->name('ordersAll.delete');

Route::get('users/all', [AppUsersController::class, 'usersAll'])->name('users.all');


Route::post('clothes/type/{id}' , [ProductsDetailsController::class , 'update_type'])->name('prodect.update_type');

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale() . "/admin",
        // 'prefix' => "admin",
        'middleware' => ['auth']
    ], function () {
    Route::controller(\App\Http\Controllers\CountryController::class)->group(function () {
        Route::get('country', 'country')->name('country');

        Route::get('country/get', 'get_country')->name('get_country');

        Route::post('country/add', 'add_country')->name('add_country');

        Route::get('country/edit/{id}', 'edit')->name('country.edit');

        Route::post('country/update/{id}', 'update')->name('country.update');

        Route::delete('country/delete/{id}', 'delete')->name('country.delete');
    });
});

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale()."/admin",
        'middleware' => ['auth']
    ] , function (){
    Route::controller(\App\Http\Controllers\ItemController::class)->group(function () {
        Route::get('item', 'index')->name('item');

//        Route::get('categories/show/{id}', 'show')->name('category.show');

        Route::get('item/get', 'get_item')->name('get_item');

//        Route::post('item/sortable', 'update_sort_order')->name('categories_sortable');

        Route::get('item/show/{id}', 'show_item')->name('show_item');

        Route::post('item/add' , 'add_item')->name('add_item');

        Route::get('item/edit/{id}' , 'edit')->name('item.edit');

        Route::post('item/update/{id}' , 'update')->name('item.update');

        Route::delete('item/delete/{id}' , 'delete')->name('item.delete');


    });
});
Route::group(
    [
        'prefix' => LaravelLocalization::setLocale()."/admin",
        'middleware' => ['auth']
    ] , function (){


    Route::resource('packages', 'App\Http\Controllers\PackagesController');


//    Route::get('city_filter', [AdsController::class, 'getCountry'])->name("cityFilter");
//Route::get('getSubCatEdit', [AdsController::class, 'getSubCatEdit'])->name("getSubCatEdit");
    Route::get('input-filter', 'App\Http\Controllers\AdsController@getInputs')->name("inputFilter");
//Route::post('add-store', [AdsController::class, 'store'])->name("addStore")->middleware('api');

    Route::post('/packages/update/status','App\Http\Controllers\PackagesController@updateStatus')->name('packages.update.status');

});



require __DIR__ . '/admin.php';
require __DIR__ . '/client.php';
require __DIR__ . '/category.php';
require __DIR__ . '/roles.php';
require __DIR__ . '/setting.php';
require __DIR__ . '/prodect.php';
require __DIR__ . '/coupon.php';
require __DIR__ . '/ads.php';
require __DIR__ . '/app_user.php';
require __DIR__ . '/contact.php';
require __DIR__ . '/payment.php';
require __DIR__ . '/governorat.php';
require __DIR__ . '/city.php';
require __DIR__ . '/delivery.php';
require __DIR__ . '/deliverytype.php';
require __DIR__ . '/time.php';
require __DIR__ . '/order.php';
require __DIR__ . '/report.php';
require __DIR__ . '/archives.php';
require __DIR__ . '/front.php';
