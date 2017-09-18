<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');

    $router->group(['prefix' => 'api'], function ($api) {
         $api->any('interface/{type}', 'ApiController@interfaces')->where('type', 'cy|po');
    });

    $router->resource('interfaces/cy', 'CyInterfaceController');
    $router->resource('interfaces/po', 'PoInterfaceController');
    $router->resource('splitmode/cy', 'CySplitModeController');

});
