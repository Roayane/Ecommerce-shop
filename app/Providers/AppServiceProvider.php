<?php

namespace App\Providers;

use App\Contracts\Payment\IPaymentGateway;
use App\Contracts\Payment\IPaymentInterceptor;
use App\Implementations\Interceptors\KlarnaInterceptor;
use App\Implementations\PaymentGateways\KlarnaGateway;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(IPaymentGateway::class, KlarnaGateway::class);
        $this->app->bind(IPaymentInterceptor::class, KlarnaInterceptor::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
