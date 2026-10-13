<?php

namespace App\Console\Commands;

use App\Models\Theme;
use App\Support\ContentConverter;
use App\Support\Region;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

/**
 * Makes the wording editable on a live site.
 *
 * The server's theme holds whatever the owner has edited, which is not the same as the
 * templates in git - so instead of overwriting their pages with ours, this applies the
 * same field maps to the theme content already in the database.
 *
 *   php artisan content:convert-theme --pretend     # report only
 *   php artisan content:convert-theme               # every region's active theme
 */
class ConvertThemeContent extends Command
{
    protected $signature = 'content:convert-theme
        {--region= : Only this region (uk, bd); defaults to all}
        {--maps= : Folder of field maps, defaults to resources/content-maps}
        {--pretend : Show what would change without saving}';

    protected $description = 'Make the live theme wording editable, without overwriting the owner\'s content';

    public function handle(): int
    {
        $mapsDir = $this->option('maps') ?: resource_path('content-maps');

        if (! File::isDirectory($mapsDir)) {
            $this->error("No field maps at {$mapsDir}");

            return self::FAILURE;
        }

        $regions = $this->option('region') ? [$this->option('region')] : array_keys(Region::REGIONS);
        $pretend = (bool) $this->option('pretend');
        $problems = 0;

        foreach ($regions as $region) {
            $theme = Region::withoutScope(fn () => Theme::withoutGlobalScopes()
                ->where('region', $region)
                ->where('is_active', true)
                ->first());

            if (! $theme) {
                $this->warn(strtoupper($region).': no active theme, skipped');
                continue;
            }

            $this->line(strtoupper($region).": \"{$theme->name}\"");
            $problems += $this->convertTheme($theme, $region, $mapsDir, $pretend);
        }

        if ($pretend) {
            $this->newLine();
            $this->comment('Nothing was saved (--pretend). Re-run without it to apply.');
        }

        return $problems === 0 ? self::SUCCESS : self::FAILURE;
    }

    private function convertTheme(Theme $theme, string $region, string $mapsDir, bool $pretend): int
    {
        $language = $region === Region::BD ? 'bn' : 'uk';
        $files = $theme->files ?? [];
        $problems = 0;
        $changedPages = 0;
        $totalFields = 0;

        foreach (File::files($mapsDir) as $mapFile) {
            if ($mapFile->getExtension() !== 'json') {
                continue;
            }

            $page = $mapFile->getFilenameWithoutExtension();
            $fields = json_decode(File::get($mapFile->getPathname()), true);

            if (! is_array($fields) || empty($files[$page] ?? null)) {
                continue;   // page not in this theme, or an unreadable map
            }

            $result = ContentConverter::apply($files[$page], $page, $fields, $language);

            if ($result['error']) {
                $this->error("   {$page}: {$result['error']} - left unchanged");
                $problems++;
                continue;
            }

            if ($result['converted'] === 0) {
                continue;   // already done, or nothing matched
            }

            $files[$page] = $result['source'];
            $changedPages++;
            $totalFields += $result['converted'];

            $missing = $result['missing'] ? ' | not found: '.count($result['missing']) : '';
            $this->line("   {$page}: {$result['converted']} field(s){$missing}");
        }

        if (! $pretend && $changedPages > 0) {
            Region::withoutScope(fn () => $theme->update(['files' => $files]));
            $theme->flushViews();
        }

        $this->info("   -> {$totalFields} field(s) across {$changedPages} page(s)".($pretend ? ' (not saved)' : ' saved'));

        return $problems;
    }
}
