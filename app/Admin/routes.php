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
    $router->resource('/splitmode/settle', 'SettleSplitModeController');

    //交易分组
    $router->any('/group/{id}/payments', 'RechargeGroupController@payments')->name('group.payments');
    $router->resource('/group/recharge', 'RechargeGroupController')->names([
        'index' => 'group.recharge.index'
    ]);


    //平台用户管理
    $router->any('/platusers/{id}/payments', 'PlatUserController@payments')->name('platusers.payments');
    $router->resource('/platusers', 'PlatUserController');
    $router->get('/profiles/detail/{id}', 'PlatUserProfileController@showProfile');
    $router->resource('/profiles', 'PlatUserProfileController');
    $router->resource('/assets', 'AssetCountController');


    //系统配置
    $router->resource('settings/bsscope', 'BusinessScopeController');

    $router->group(['prefix' => 'api', 'middleware' => ['web']], function($r) {
        $r->get('getifs/{type}', 'ApiController@getIfsFromPm')->name('getifs');
        $r->get('{type}/{id}/pmadd', 'ApiController@addPayment')->name('group.pmadd');
        $r->get('group/rechargemode', 'ApiController@getRechargeMode')->name('group.rechargemode');
        $r->post('profiles/audit/{id}', 'ApiController@auditProfile')->name('profiles.audit');
        $r->get('asset/refresh', 'ApiController@assetRefresh')->name('asset.refresh');
    });


});
