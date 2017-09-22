<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');
    //通道字典
    $router->resource('/payments/dict', 'DictPaymentController');
    //接口字典
    $router->resource('/interfaces/dict', 'DictInterfaceController');

    $router->any('/interfaces/recharge/{id}/setRate', 'RechargeIfController@setRate')->name('setrate');
    $router->resource('/interfaces/recharge', 'RechargeIfController');
    $router->resource('/interfaces/settlement', 'SettlementIfController');

    //交易模式
    $router->resource('/splitmode/recharge', 'RechargeSplitModeController');

    //交易分组
    $router->any('/group/{id}/payments', 'RechargeGroupController@payments')->name('group.payments');
    $router->resource('/group/recharge', 'RechargeGroupController');

    //平台用户管理
    $router->resource('/platuser', 'PlatUserController');

    $router->group(['prefix' => 'api'], function($r) {
        $r->get('getifs/{type}', 'ApiController@getIfsFromPm')->name('getifs');
        $r->get('group/{id}/pmadd', 'ApiController@addPayment')->name('group.pmadd');
    });


});
