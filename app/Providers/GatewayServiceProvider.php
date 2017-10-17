<?php

namespace App\Providers;

use App\Services\Gateway\RechargeGatewayService;
use App\Services\PlatUserService;
use Illuminate\Support\ServiceProvider;

class GatewayServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->bind('App\Services\Gateway\RechargeGatewayService', function ($app) {
            return new RechargeGatewayService();
        });
    }
}
