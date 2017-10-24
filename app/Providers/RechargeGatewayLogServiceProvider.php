<?php

namespace App\Providers;

use App\Services\Gateway\RechargeLogService;
use Illuminate\Support\ServiceProvider;

class RechargeGatewayLogServiceProvider extends ServiceProvider
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
        $this->app->singleton('RechargeLog', function ($app) {
            return new RechargeLogService();
        });
    }
}
