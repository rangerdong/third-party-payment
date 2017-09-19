<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');
    //通道字典表
    $router->resource('/payments/dict', 'DictPaymentController');
    $router->resource('/interfaces/dict', 'DictInterfaceController');
    $router->resource('/interfaces/recharge', 'RechargeIfController');
    $router->resource('/interfaces/settlement', 'SettlementIfController');



});
