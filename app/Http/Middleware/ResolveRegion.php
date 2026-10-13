<?php

namespace App\Http\Middleware;

use App\Support\PageContentStore;
use App\Support\Region;
use Closure;
use Illuminate\Http\Request;

/**
 * Decides which country's site this request is for, from the hostname
 * (bd.example.com -> Bangladesh). Everything downstream - model scopes, currency,
 * timezone, the active theme - follows from this.
 */
class ResolveRegion
{
    public function handle(Request $request, Closure $next)
    {
        // Region::current() resolves from the request itself, so nothing is set here;
        // this only applies the matching locale and timezone.
        // Edited page content is cached per request; a save must show on the next load.
        app(PageContentStore::class)->flush();

        $config = Region::config(Region::current() ?? Region::UK);
        config(['app.timezone_display' => $config['timezone']]);
        app()->setLocale($config['locale']);

        return $next($request);
    }
}
