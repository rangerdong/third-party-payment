<?php

namespace App\Providers;

use App\Models\RechargeOrder;
use App\Services\Gateway\RechargeGatewayService;
use App\Services\PlatUserService;
use App\Services\Recharge\ThirdPayments\Contracts\ThirdPaymentAbstract;
use App\Services\RechargeOrderService;
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
//        $this->app->bind('RechargeGateway', RechargeOrderService::class);
//        $this->app->bind('ThirdPaymentAbstract', ThirdPaymentAbstract::class);
        $this->app->bind('RechargeOrder', RechargeOrderService::class);
    }
}
