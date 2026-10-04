<?php

namespace App\Http\Middleware;

use App\Support\Region;
use Closure;
use Illuminate\Http\Request;

/**
 * Admin screens work across every country. The region picker in the header stores a
 * choice in the session: a specific region narrows the lists, "all" clears the scope
 * so every record is visible.
 */
class AdminRegionContext
{
    public const SESSION_KEY = Region::ADMIN_SESSION_KEY;

    public function handle(Request $request, Closure $next)
    {
        $choice = $request->session()->get(self::SESSION_KEY, 'all');

        // Admin pages always price and format in the chosen region, defaulting to the UK.
        app()->setLocale(Region::config($choice === 'all' ? Region::UK : $choice)['locale']);

        return $next($request);
    }
}
