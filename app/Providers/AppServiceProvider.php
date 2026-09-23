<?php

namespace App\Providers;

use App\Models\Customer;
use App\Support\Permissions;
use Illuminate\Auth\Middleware\Authenticate as AuthenticateMiddleware;
use Illuminate\Support\Facades\Gate;
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

        // One gate per permission, so routes can use the built-in "can:bookings.view" middleware.
        foreach (Permissions::all() as $permission) {
            Gate::define($permission, fn ($user) => method_exists($user, 'hasPermission') && $user->hasPermission($permission));
        }
    }
}
