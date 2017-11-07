<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//buz api
Route::group([
    'domain' => config('app.website.BUZ_DOMAIN'),
    'namespace' => 'Api',
    'middleware' => ['api']
], function ($router) {
    $router->group(['namespace' => 'Buz'], function ($router) {
        $router->post('auth/login', 'AuthController@login');
        $router->post('auth/register', 'AuthController@register')->middleware('sms-verify');

        $router->post('sms/send', 'SMSController@sendSms');

        $router->get('auth/info', 'AuthController@getAuthUser')->middleware('jwt-auth');
    });

});
