<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

/**
 * Lets an admin open the real website inside the admin and edit the copy in place.
 *
 * The Bangladesh site is a different host, so the admin's session cookie is not sent
 * with it. Instead the admin issues a short-lived token, which the preview URL carries
 * and this class validates - the page is only put into edit mode for that token.
 */
class ContentEditMode
{
    public const PARAM = 'content_edit';

    private const TTL_MINUTES = 60;

    private static ?bool $active = null;

    public static function issueToken(string $page): string
    {
        $token = Str::random(40);

        Cache::put(self::cacheKey($token), $page, now()->addMinutes(self::TTL_MINUTES));

        return $token;
    }

    /** True when this request should render editable markers. */
    public static function active(?Request $request = null): bool
    {
        if (self::$active !== null) {
            return self::$active;
        }

        $request ??= request();
        $token = $request?->query(self::PARAM);

        return self::$active = is_string($token) && $token !== '' && Cache::has(self::cacheKey($token));
    }

    /** The page key the token was issued for, used to ignore markers from other pages. */
    public static function page(?Request $request = null): ?string
    {
        $request ??= request();
        $token = $request?->query(self::PARAM);

        return is_string($token) ? Cache::get(self::cacheKey($token)) : null;
    }

    public static function forget(): void
    {
        self::$active = null;
    }

    /**
     * Markers survive Blade's HTML escaping (they are plain characters), so the text
     * itself stays escaped and the middleware turns these into real elements afterwards.
     */
    public static function wrap(string $page, string $key, string $type, string $value): string
    {
        return "\u{2063}CF:{$page}:{$key}:{$type}\u{2063}".$value."\u{2063}/CF\u{2063}";
    }

    private static function cacheKey(string $token): string
    {
        return 'content-edit:'.hash('sha256', $token);
    }
}
