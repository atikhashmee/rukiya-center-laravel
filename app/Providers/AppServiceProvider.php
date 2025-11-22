<?php

namespace App\Providers;

use App\Models\Customer;
use Illuminate\Auth\Middleware\Authenticate as AuthenticateMiddleware;
use Illuminate\Support\ServiceProvider;
use Laravel\Cashier\Cashier;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Cashier::useCustomerModel(Customer::class);
        AuthenticateMiddleware::redirectUsing(function ($request) {
            if ($request->expectsJson()) {
                return null;
            }
            if ($request->is('customer/*') || $request->routeIs('customer.*')) {
                return route('customer.login');
            }

            return route('login');
        });
    }
}
