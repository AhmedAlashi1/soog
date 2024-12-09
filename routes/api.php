<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::get('test',function (){
    return 'a';
});


Route::group(['prefix' => 'v3'], function () {
    Route::post('home', [\App\Http\Controllers\Api\V3\ClothesController::class,'home']);

    Route::post('country',   [\App\Http\Controllers\Api\V3\ClothesController::class,'country'])->name('country');
    Route::any('setting', [\App\Http\Controllers\Api\V3\ClothesController::class,'setting']);
    Route::post('about', [\App\Http\Controllers\Api\V3\ClothesController::class,'about']);
    Route::any('cities', [\App\Http\Controllers\Api\V3\CountriesController::class,'getCities']);
    Route::post('categories', [\App\Http\Controllers\Api\V3\ClothesController::class,'getData']);
    Route::post('packages', [\App\Http\Controllers\Api\V3\ClothesController::class,'packages']);
    Route::post('payment', [\App\Http\Controllers\Api\V3\ClothesController::class,'payment']);


});




Route::group(['prefix' => 'user', 'middleware' => ['api']], function () {

    Route::get('authorized/google', [\App\Http\Controllers\Api\V1\UserController::class, 'redirectToGoogle']);
    Route::get('authorized/google/callback', [\App\Http\Controllers\Api\V1\UserController::class, 'handleGoogleCallback']);

    Route::post('auth', ['as' => 'api-auth', 'uses' => 'Api\V1\AuthController@auth']);
    Route::post('forgetPass', ['as' => 'api-forget-pass', 'uses' => 'Api\V1\AuthController@forgetPassword']);
    Route::post('verifyCode', ['as' => 'api-auth-verify-ode', 'uses' => 'Api\V1\AuthController@verifyCode']);

    Route::post('register', [\App\Http\Controllers\Api\V1\UserController::class,'register']);
    Route::post('auth/refresh', [\App\Http\Controllers\Api\V1\AuthController::class,'refreshToken']);

    Route::post('update/token',[\App\Http\Controllers\Api\V1\UserController::class,'tokenFcm'])->middleware('api');


    Route::post('activateAccount', [\App\Http\Controllers\Api\V1\UserController::class,'activateAccount'])->middleware('api');
    Route::post('resendActivation', [\App\Http\Controllers\Api\V1\UserController::class,'resendActivation'])->middleware('api');
    Route::post('logout', [\App\Http\Controllers\Api\V1\AuthController::class,'logout'])->middleware('api');
    Route::post('profile', [\App\Http\Controllers\Api\V1\UserController::class,'profile'])->middleware('api');
    Route::post('update', [\App\Http\Controllers\Api\V1\UserController::class,'updateInfo'])->middleware('api');

    Route::post('/getFollow', [\App\Http\Controllers\Api\V1\UserController::class,'getFollow'])->middleware('api');
    Route::post('/addFollow', [\App\Http\Controllers\Api\V1\UserController::class,'addFollow'])->middleware('api');
    Route::post('/deleteFollow', [\App\Http\Controllers\Api\V1\UserController::class,'deleteFollow'])->middleware('api');


    Route::post('/reportUser', [\App\Http\Controllers\Api\V1\UserController::class,'reportUser'])->middleware('api');
        Route::post('/blockUser', [\App\Http\Controllers\Api\V1\UserController::class,'BlockUser'])->middleware('api');

        Route::post('/deleteUser', [\App\Http\Controllers\Api\V1\UserController::class,'deleteUser'])->middleware('api');



    Route::any('notification', [\App\Http\Controllers\Api\V1\NotificationController::class,'getData'])->middleware('api');
    Route::any('chat/notification', [\App\Http\Controllers\Api\V1\NotificationController::class,'chat'])->middleware('api');
    Route::post('notification/product', [\App\Http\Controllers\Api\V1\NotificationController::class,'multi_product'])->middleware('api');
    Route::get('read/{id}', [\App\Http\Controllers\Api\V1\NotificationController::class,'read'])->middleware('api');





});

Route::group(['prefix' => 'advertisements', 'middleware' => ['api']], function () {
    Route::post('createProperty', [\App\Http\Controllers\Api\V1\ClothesController::class,'createProperty'])->middleware('api');
    Route::post('updateProperty', [\App\Http\Controllers\Api\V1\ClothesController::class,'updateProperty'])->middleware('api');
    Route::post('updatePropertySale', [\App\Http\Controllers\Api\V1\ClothesController::class,'updatePropertySale'])->middleware('api');


    Route::get('deleteProperty/{id}', [\App\Http\Controllers\Api\V1\ClothesController::class,'deleteProperty'])->middleware('api');
    Route::get('deleteImage/{id}', [\App\Http\Controllers\Api\V1\ClothesController::class,'deleteImage'])->middleware('api');

    Route::post('index/{cat_id}', [\App\Http\Controllers\Api\V1\ClothesController::class,'index']);
    Route::post('index_all', [\App\Http\Controllers\Api\V1\ClothesController::class,'all']);
    Route::post('details', [\App\Http\Controllers\Api\V1\ClothesController::class,'getRow']);

    Route::post('fixed_ads', [\App\Http\Controllers\Api\V1\ClothesController::class,'fixed_ads'])->middleware('api');

    Route::post('/getFav', [\App\Http\Controllers\Api\V1\ClothesController::class,'getFav'])->middleware('api');
    Route::post('/addFav', [\App\Http\Controllers\Api\V1\ClothesController::class,'addFav'])->middleware('api');
    Route::post('/deleteFav', [\App\Http\Controllers\Api\V1\ClothesController::class,'deleteFav'])->middleware('api');

});



Route::get('callback/success',   [\App\Http\Controllers\Api\V1\CountriesController::class,'ordersSuccess'])->name('ordersSuccess');
Route::get('callback/error',   [\App\Http\Controllers\Api\V1\CountriesController::class,'ordersError'])->name('ordersError');

Route::get('callback/paymentStatus',   [\App\Http\Controllers\Api\V1\ClothesController::class,'paymentStatus'])->name('paymentStatus');

Route::post('cities', ['as' => 'api-get-cities', 'middleware' => ['api', 'settings', 'https'], 'uses' => 'Api\V1\CountriesController@getRegion']);

Route::post('contactUs', [\App\Http\Controllers\Api\V1\ClothesController::class,'contactUs']);

