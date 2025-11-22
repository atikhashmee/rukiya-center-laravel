<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate;

class AuthenticateCustomer extends Authenticate
{
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('customer.login');
        }
    }
}
