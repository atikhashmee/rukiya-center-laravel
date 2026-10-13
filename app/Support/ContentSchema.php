<?php

namespace App\Support;

use App\Models\Theme;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * Works out which editable fields a page has by reading its template and finding
 * the content() / content_html() / content_image() calls. The template is the
 * single source of truth, so converting a page is all it takes for its fields to
 * appear in the admin - there is no separate schema to keep in step.
 */
class ContentSchema
{
    private const HELPERS = [
        'content' => 'text',
        'content_html' => 'html',
        'content_image' => 'image',
    ];

    /** Fields for a page, in the order they appear in the template. */
    public static function forPage(string $page, ?string $region = null): array
    {
        return static::fromSource(static::templateSource($page, $region), $page);
    }

    /** The live template for a page: the active theme's copy, else the shipped file. */
    public static function templateSource(string $page, ?string $region = null): string
    {
        $theme = Region::withoutScope(fn () => Theme::withoutGlobalScopes()
            ->where('region', $region ?? Region::current() ?? Region::UK)
            ->where('is_active', true)
            ->first());

        $stored = $theme?->getFileContent($page);

        if (! empty($stored)) {
            return $stored;
        }

        $filename = Theme::EDITABLE_FILES[$page] ?? null;
        $path = $filename ? resource_path("views/Themes/{$filename}") : null;

        return $path && File::exists($path) ? File::get($path) : '';
    }

    /** Parse content*() calls out of template source. */
    public static function fromSource(string $source, string $page): array
    {
        $fields = [];

        foreach (self::HELPERS as $helper => $type) {
            foreach (static::calls($source, $helper) as $call) {
                [$callPage, $key, $default] = array_pad($call['args'], 3, null);

                if ($callPage !== $page || $key === null) {
                    continue;
                }

                $fields[$key] ??= [
                    'key' => $key,
                    'type' => $type,
                    'label' => Str::of($key)->replace('_', ' ')->ucfirst()->toString(),
                    'default' => $default ?? '',
                    'position' => $call['position'],
                ];
            }
        }

        usort($fields, fn ($a, $b) => $a['position'] <=> $b['position']);

        return array_values($fields);
    }

    /**
     * Find calls to one helper and split their arguments, respecting quotes and
     * nested parentheses so defaults like asset('x.png') survive.
     */
    private static function calls(string $source, string $helper): array
    {
        $calls = [];
        $offset = 0;
        $needle = $helper.'(';

        while (($start = strpos($source, $needle, $offset)) !== false) {
            $offset = $start + strlen($needle);

            // "content(" must not be the tail of another identifier such as "page_content("
            $before = $start > 0 ? $source[$start - 1] : ' ';
            if (preg_match('/[A-Za-z0-9_]/', $before)) {
                continue;
            }

            $args = static::splitArguments($source, $offset);

            if ($args !== null) {
                $calls[] = ['args' => $args, 'position' => $start];
            }
        }

        return $calls;
    }

    /** Read arguments from after the opening paren up to its matching close. */
    private static function splitArguments(string $source, int $from): ?array
    {
        $depth = 1;
        $args = [];
        $current = '';
        $quote = null;
        $length = strlen($source);

        for ($i = $from; $i < $length; $i++) {
            $char = $source[$i];

            if ($quote !== null) {
                if ($char === '\\' && $i + 1 < $length) {
                    $current .= $char.$source[$i + 1];
                    $i++;
                    continue;
                }

                if ($char === $quote) {
                    $quote = null;
                }

                $current .= $char;
                continue;
            }

            if ($char === '"' || $char === "'") {
                $quote = $char;
                $current .= $char;
                continue;
            }

            if ($char === '(') {
                $depth++;
            } elseif ($char === ')') {
                $depth--;

                if ($depth === 0) {
                    $args[] = $current;

                    return array_map([static::class, 'literal'], $args);
                }
            }

            if ($char === ',' && $depth === 1) {
                $args[] = $current;
                $current = '';
                continue;
            }

            $current .= $char;
        }

        return null;   // unbalanced: ignore this call rather than guess
    }

    /** Turn a PHP argument back into its string value, or null when it isn't a plain string. */
    private static function literal(string $argument): ?string
    {
        $argument = trim($argument);

        if ($argument === '') {
            return null;
        }

        $quote = $argument[0];

        if (($quote === "'" || $quote === '"') && str_ends_with($argument, $quote) && strlen($argument) > 1) {
            $inner = substr($argument, 1, -1);

            return $quote === "'"
                ? str_replace(["\\'", '\\\\'], ["'", '\\'], $inner)
                : stripcslashes($inner);
        }

        return null;   // e.g. asset('images/hero.png') - no literal default to show
    }
}
