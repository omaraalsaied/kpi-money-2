<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
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

// Route::get('testing', function(){return response()->json('working fine new route');});
// dashboard route  
Route::get('/dashboard','AdminController@index')->name('dashboard');

Route::get('/', 'HomeController@defaultHome');
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::get('/verify/auth/{token}/{enc}', 'Api\UserController@verifyEmail');


Route::group(['middleware' => 'auth'], function(){
	// logout route
	Route::get('/logout', 'Auth\LoginController@logout');
	Route::get('/clear-cache', 'HomeController@clearCache');
	Route::get('/call', 'HomeController@call');
	Route::get('/users', 'CustomerController@index');
	Route::get('/users/delete/{id}', 'CustomerController@destroy');
	Route::get('/users/banned', 'CustomerController@bannedindex');
	Route::post('/users/status', 'CustomerController@status');
	Route::get('/user/get-list/{status}', 'CustomerController@getUserList');
	Route::get('/user-transaction', 'TransactionController@index');
	Route::get('/user/track/{id}', 'TransactionController@usertrack');
	Route::get('/transaction/data', 'TransactionController@getTransacitonList');
	Route::get('/transaction/{id}', 'TransactionController@getUserTransaciton');
	Route::get('/setting-general','SettingController@index');
	Route::post('/setting/update', 'SettingController@update');
	Route::get('/setting/ads', 'SettingController@ads');
	Route::get('/setting/spin', 'SettingController@spin');
	Route::get('/setting/app', 'SettingController@app');
	Route::post('/setting/app-setting', 'SettingController@appupdate');
	Route::post('/setting/spinupdate', 'SettingController@spinupdate');
	Route::view('/notification', 'notification');
	Route::post('/notification/send', 'NotificationController@new');
	Route::get('/admin-profile', 'AdminController@admin');
	Route::post('/admin/update', 'AdminController@update');
	Route::post('/verify', 'AdminController@verify');

	//web
	Route::get('/websites', 'WeblinkController@index');
	Route::get('/websites/list', 'WeblinkController@List');
	Route::get('/websites/create-websites', function () { return view('web.create-web'); });
	Route::post('/websites/create', 'WeblinkController@store');
	Route::get('/websites/edit/{id}', 'WeblinkController@edit');
	Route::post('/websites/update', 'WeblinkController@update');
	Route::get('/websites/delete/{id}', 'WeblinkController@destroy');
	
	//video
	Route::get('/videos', 'VideoController@index');
	Route::get('/videos/list', 'VideoController@List');
	Route::get('/videos/create-video', function () { return view('video.create-video'); });
	Route::post('/videos/create', 'VideoController@store');
	Route::get('/videos/edit/{id}', 'VideoController@edit');
	Route::post('/videos/update', 'VideoController@update');
	Route::get('/videos/delete/{id}', 'VideoController@destroy');
	
	//apps
	Route::get('/apps', 'AppsController@index');
	Route::get('/apps/list', 'AppsController@List');
	Route::get('/apps/create-app', function () { return view('app.create-app'); });
	Route::post('/apps/create', 'AppsController@store');
	Route::get('/apps/edit/{id}', 'AppsController@edit');
	Route::post('/apps/update', 'AppsController@update');
	Route::get('/apps/delete/{id}', 'AppsController@destroy');

	//rewards
	Route::get('/payment-options', 'RedeemController@index');
	Route::get('/payment-options/list', 'RedeemController@List');
	Route::get('/payment-options/create', function () { return view('redeem.create-redeem'); });
	Route::post('/payment-options/create', 'RedeemController@store');
	Route::get('/payment-options/edit/{id}', 'RedeemController@edit');
	Route::post('/payment-options/update', 'RedeemController@update');
	Route::get('/payment-options/delete/{id}', 'RedeemController@destroy');
	
	// home offer
	Route::get('/offer', 'OfferController@index');
	Route::get('/offer/list', 'OfferController@list');
	Route::get('/offer/edit/{id}', 'OfferController@edit');
	Route::post('/offer/update', 'OfferController@update');
	Route::post('/offer/action', 'OfferController@action');

	//banner
	Route::get('/banner', 'BannerController@index');
	Route::get('/banner/list', 'BannerController@List');
	Route::post('/banner/create', 'BannerController@store');
	Route::post('/banner/action', 'BannerController@action');
	Route::get('/banner/edit/{id}', 'BannerController@edit');
	Route::post('/banner/update', 'BannerController@update');
	Route::get('/banner/delete/{id}', 'BannerController@destroy');
	
	//games
	Route::get('/games', 'GameController@index');
	Route::get('/games/list', 'GameController@List');
	Route::post('/games/create', 'GameController@store');
	Route::post('/games/action', 'GameController@action');
	Route::get('/games/edit/{id}', 'GameController@edit');
	Route::post('/games/update', 'GameController@update');
	Route::get('/games/delete/{id}', 'GameController@destroy');
	
	//request pending
	Route::get('/request/completelist', 'PaymentController@completelist'); // this one
	Route::get('/request-pending', 'PaymentController@index');
	Route::get('/request/pendinglist', 'PaymentController@pendinglist');
	
	//request reject
	Route::get('/request-reject', 'PaymentController@viewreject');
	Route::get('/request/rejectlist', 'PaymentController@rejectlist');
	
	Route::post('/request/update', 'PaymentController@update');
	Route::get('/request/delete/{id}', 'PaymentController@destroy');
	Route::get('/request/{id}', 'PaymentController@list');

	//request complete
	Route::get('/request-complete', 'PaymentController@viewcomplete');



	
});

