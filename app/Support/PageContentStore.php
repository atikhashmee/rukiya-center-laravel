<?php

namespace App\Support;

use App\Models\PageContent;

/**
 * Per-request cache of edited page content, so a page with twenty fields runs one
 * query instead of twenty. Cleared at the start of every request (see ResolveRegion)
 * because a saved value must be visible on the very next page load.
 */
class PageContentStore
{
    /** @var array<string, array<string, array<string, string|null>>> */
    private array $pages = [];

    public function get(string $page, string $key, string $default = '', ?string $region = null): string
    {
        $region ??= Region::current() ?? Region::UK;

        $this->pages[$region][$page] ??= PageContent::forPage($page, $region);

        $value = $this->pages[$region][$page][$key] ?? null;

        return ($value === null || $value === '') ? $default : $value;
    }

    public function flush(): void
    {
        $this->pages = [];
    }
}
