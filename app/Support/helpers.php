<?php

use App\Support\ContentEditMode;
use App\Support\PageContentStore;

if (! function_exists('content')) {
    /**
     * Client-editable text in a theme template.
     *
     *   {{ content('index', 'hero_title', 'Authentic Ruqyah') }}
     *
     * The third argument is what the site shows until someone edits it in the
     * admin, so templates keep working before anything is saved. Values are
     * stored per region.
     */
    function content(string $page, string $key, string $default = ''): string
    {
        $value = app(PageContentStore::class)->get($page, $key, $default);

        return ContentEditMode::active()
            ? ContentEditMode::wrap($page, $key, 'text', $value)
            : $value;
    }
}

if (! function_exists('content_html')) {
    /** Rich text (edited with a visual editor). Output with {!! !!}. */
    function content_html(string $page, string $key, string $default = ''): string
    {
        $value = app(PageContentStore::class)->get($page, $key, $default);

        return ContentEditMode::active()
            ? ContentEditMode::wrap($page, $key, 'html', $value)
            : $value;
    }
}

if (! function_exists('content_image')) {
    /** Image URL, replaceable by an upload in the admin. */
    function content_image(string $page, string $key, string $default = ''): string
    {
        $value = app(PageContentStore::class)->get($page, $key, $default);

        // Images sit inside attributes, so they cannot be wrapped: tag the URL instead
        // and the editor script finds them by that marker.
        return ContentEditMode::active()
            ? $value.(str_contains($value, '?') ? '&' : '?')."cf={$page}:{$key}"
            : $value;
    }
}
