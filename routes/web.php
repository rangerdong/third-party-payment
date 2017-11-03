<?php

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

Route::group(['domain' => config('app.website.HOME_DOMAIN')], function () {
    Route::get('/', function () {
        return view('welcome');
    });
});


Route::group(['domain' => config('app.website.GATEWAY_DOMAIN'), 'namespace' => 'Gateway'], function ($router) {

    $router->group(['prefix' => 'recharge'], function ($router) {
        $router->any('pay', 'RechargeGatewayController@pay')->name('gateway.recharge.pay');
        $router->any('callback/{identify}', 'RechargeGatewayController@callback')->name('gateway.recharge.callback');
        $router->any('return/{identify}', 'RechargeGatewayController@returnHref')->name('gateway.recharge.return');

    });

});

//buz
Route::group([
    'domain' => config('app.website.BUZ_DOMAIN'),
    'namespace' => 'Buz',
], function ($router) {
});



Route::group(['namespace' => 'Gateway\Page'], function ($r) {
    $r->get('recharge/pay',function () {
        return view('gateway.recharge.pay');
    });
    $r->post('recharge/pay', 'RechargeController@pay');
    $r->any('recharge/callback', 'RechargeController@callback')->name('page.callback');
});
