<?php

namespace App\Support;

/**
 * The site runs one codebase for several countries. Which region a request belongs to
 * is decided by the host (bd.example.com -> "bd"), and held here for the rest of the
 * request so models can scope themselves to it.
 *
 * Admin screens set the context to null, meaning "all regions".
 */
class Region
{
    public const ADMIN_SESSION_KEY = 'admin_region';

    public const UK = 'uk';
    public const BD = 'bd';

    public const REGIONS = [
        self::UK => [
            'label' => 'United Kingdom',
            'short' => 'UK',
            'currency' => 'GBP',
            'symbol' => '£',
            'locale' => 'en',
            'timezone' => 'Europe/London',
            'phone_country' => 'GB',
            'subdomain' => null,     // the bare domain
        ],
        self::BD => [
            'label' => 'Bangladesh',
            'short' => 'BD',
            'currency' => 'BDT',
            'symbol' => '৳',
            'locale' => 'bn',
            'timezone' => 'Asia/Dhaka',
            'phone_country' => 'BD',
            'subdomain' => 'bd',
        ],
    ];

    /** Region the current request belongs to, or null in the admin ("all regions"). */
    protected static ?string $current = null;

    /** True once something has set the region explicitly (middleware, tests, jobs). */
    protected static bool $explicit = false;

    /**
     * Resolved lazily from the request so it is correct no matter when it is asked -
     * route model binding runs before route middleware, so relying on middleware
     * ordering would 404 on another region's records in the admin.
     */
    public static function current(): ?string
    {
        if (static::$explicit) {
            return static::$current;
        }

        $request = request();

        if (! $request) {
            return self::UK;
        }

        // Admin screens follow the region picker; "all" means no filter at all.
        if ($request->is('admin', 'admin/*')) {
            $choice = $request->hasSession() && $request->session()->isStarted()
                ? $request->session()->get(self::ADMIN_SESSION_KEY, 'all')
                : 'all';

            return $choice === 'all' ? null : (isset(self::REGIONS[$choice]) ? $choice : null);
        }

        return self::fromHost($request->getHost());
    }

    public static function setCurrent(?string $region): void
    {
        static::$explicit = true;
        static::$current = $region && isset(self::REGIONS[$region]) ? $region : ($region === null ? null : self::UK);
    }

    /** Hand region resolution back to the request (used between tests). */
    public static function forget(): void
    {
        static::$explicit = false;
        static::$current = null;
    }

    /** Region for a hostname: the "bd." subdomain maps to Bangladesh, anything else to the UK. */
    public static function fromHost(?string $host): string
    {
        $first = strtolower(strtok((string) $host, '.'));

        foreach (self::REGIONS as $key => $config) {
            if ($config['subdomain'] !== null && $first === $config['subdomain']) {
                return $key;
            }
        }

        return self::UK;
    }

    /** Run a callback with every region visible (used by admin screens and jobs). */
    public static function withoutScope(callable $callback)
    {
        $previousRegion = static::$current;
        $previousExplicit = static::$explicit;

        static::$current = null;
        static::$explicit = true;

        try {
            return $callback();
        } finally {
            static::$current = $previousRegion;
            static::$explicit = $previousExplicit;
        }
    }

    public static function config(?string $region = null): array
    {
        return self::REGIONS[$region ?? static::$current ?? self::UK];
    }

    public static function symbol(?string $region = null): string
    {
        return static::config($region)['symbol'];
    }

    public static function currency(?string $region = null): string
    {
        return static::config($region)['currency'];
    }

    public static function label(?string $region = null): string
    {
        return static::config($region)['label'];
    }

    /** Validation rule for a region field on admin forms. */
    public static function rule(): string
    {
        return 'nullable|in:'.implode(',', array_keys(self::REGIONS));
    }

    public static function all(): array
    {
        return array_map(fn ($key) => [
            'value' => $key,
            'label' => self::REGIONS[$key]['label'],
            'short' => self::REGIONS[$key]['short'],
            'symbol' => self::REGIONS[$key]['symbol'],
            'currency' => self::REGIONS[$key]['currency'],
        ], array_keys(self::REGIONS));
    }

    /** Absolute URL of another region's site, keeping the current scheme and base domain. */
    public static function url(string $region, string $path = '/'): string
    {
        // getHttpHost() keeps the port, which matters for local development.
        $host = request()?->getHttpHost() ?: (parse_url(config('app.url'), PHP_URL_HOST) ?: 'localhost');
        $base = preg_replace('/^(' . implode('|', array_filter(array_column(self::REGIONS, 'subdomain'))) . ')\./', '', $host);
        $subdomain = self::REGIONS[$region]['subdomain'] ?? null;
        $scheme = request()?->getScheme() ?: 'https';

        return $scheme . '://' . ($subdomain ? "{$subdomain}.{$base}" : $base) . '/' . ltrim($path, '/');
    }
}
