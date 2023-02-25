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
Route::group(['middleware' => 'auth:sanctum'], function(){
    //All secure URL's
    
    Route::get('sldiebanner', 'Api\Func@sldiebanner');
    Route::post('datas', 'Api\Func@initCr');
    Route::get('redeem', 'Api\Func@fetch_rewards');
    Route::get('games', 'Api\Func@games'); // old

    });
        
    Route::get('offers', 'Api\Func@offers');
    Route::get('abouts', 'Api\Func@abouts');
    Route::post('reset-password', 'Api\UserController@reset');
    Route::post('verify-otp', 'Api\UserController@verify');
    Route::post('update_password', 'Api\UserController@update_password');
    Route::post('user', 'Api\UserController@index');
    Route::get('send_Verfiyotp/{email}', 'Api\UserController@send_Verfiyotp');

    
    
    