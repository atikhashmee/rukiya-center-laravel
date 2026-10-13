<?php

namespace App\Support;

use Illuminate\Support\Facades\Blade;

/**
 * Swaps a page's hardcoded wording for content() calls, so the site owner can edit it.
 *
 * Replacement goes through placeholders, longest string first, so a phrase that is a
 * substring of another can never land inside an already-converted call. The result is
 * compiled before it is handed back: a template that would not compile is rejected
 * rather than written.
 */
class ContentConverter
{
    /**
     * @return array{source: string, converted: int, missing: array<int, string>, error: ?string}
     */
    public static function apply(string $source, string $page, array $fields, string $language): array
    {
        $ordered = collect($fields)
            ->filter(fn ($field) => ! empty($field[$language] ?? null))
            ->sortByDesc(fn ($field) => mb_strlen($field[$language]))
            ->values();

        $placeholders = [];
        $missing = [];

        foreach ($ordered as $index => $field) {
            $text = $field[$language];

            // Already converted on an earlier run: never wrap it twice.
            if (str_contains($source, "'{$page}', '{$field['key']}'")) {
                continue;
            }

            $token = "\x00CF{$index}\x00";
            $replaced = self::replaceFirst($source, $text, $token);

            if ($replaced === $source) {
                $missing[] = $field['key'];   // absent, or only inside a comment or script
                continue;
            }

            $placeholders[$token] = $field;
            $source = $replaced;
        }

        foreach ($placeholders as $token => $field) {
            $source = str_replace($token, self::call($page, $field, $language), $source);
        }

        if (str_contains($source, "\x00")) {
            return ['source' => $source, 'converted' => 0, 'missing' => $missing, 'error' => 'placeholder left behind'];
        }

        try {
            Blade::compileString($source);
        } catch (\Throwable $e) {
            return ['source' => $source, 'converted' => 0, 'missing' => $missing, 'error' => 'template would not compile: '.$e->getMessage()];
        }

        return ['source' => $source, 'converted' => count($placeholders), 'missing' => $missing, 'error' => null];
    }

    private static function call(string $page, array $field, string $language): string
    {
        $type = $field['type'] ?? 'text';
        $value = $field[$language];

        if ($type === 'arg') {
            // The map carries the literal as written in the file ('you\'re booked.'),
            // so unescape it before re-escaping or the page shows a stray backslash.
            $inner = trim($value, "'\"");
            $inner = str_replace(["\\'", '\\"', '\\\\'], ["'", '"', '\\'], $inner);

            return "content('{$page}', '{$field['key']}', '".self::quote($inner)."')";
        }

        if ($type === 'image') {
            $default = $field['expr'] ?? "'".self::quote($value)."'";

            return "{{ content_image('{$page}', '{$field['key']}', {$default}) }}";
        }

        $helper = $type === 'html' ? 'content_html' : 'content';
        [$open, $close] = $type === 'html' ? ['{!!', '!!}'] : ['{{', '}}'];

        return "{$open} {$helper}('{$page}', '{$field['key']}', '".self::quote($value)."') {$close}";
    }

    private static function quote(string $value): string
    {
        return str_replace(['\\', "'"], ['\\\\', "\\'"], $value);
    }

    /**
     * Replace the first *real* occurrence: matches inside HTML comments or <script>
     * blocks are skipped, so a label like "Filter" is not swapped inside the
     * "<!-- Filters -->" comment that sits above the button it belongs to.
     */
    private static function replaceFirst(string $haystack, string $needle, string $replacement): string
    {
        $skip = self::ignoredRanges($haystack);
        $offset = 0;

        while (($position = strpos($haystack, $needle, $offset)) !== false) {
            $inside = false;

            foreach ($skip as [$start, $end]) {
                if ($position >= $start && $position < $end) {
                    $inside = true;
                    $offset = $end;
                    break;
                }
            }

            if (! $inside) {
                return substr_replace($haystack, $replacement, $position, strlen($needle));
            }
        }

        return $haystack;
    }

    /** Character ranges the converter must not touch: HTML comments and scripts. */
    private static function ignoredRanges(string $source): array
    {
        $ranges = [];

        foreach (['/<!--.*?-->/s', '/<script\b.*?<\/script>/is'] as $pattern) {
            if (preg_match_all($pattern, $source, $matches, PREG_OFFSET_CAPTURE)) {
                foreach ($matches[0] as [$text, $start]) {
                    $ranges[] = [$start, $start + strlen($text)];
                }
            }
        }

        return $ranges;
    }
}
