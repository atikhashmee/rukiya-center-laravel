<?php

namespace App\Console\Commands;

use App\Models\Theme;
use App\Support\Region;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

/**
 * Moves a theme's page templates between the database (what the site renders)
 * and a folder of blade files (what we can review in git and edit in bulk).
 */
class ThemeFiles extends Command
{
    protected $signature = 'theme:files {slug} {--export} {--import} {--dir=}';

    protected $description = 'Export a theme\'s pages to blade files, or import them back';

    public function handle(): int
    {
        $theme = Region::withoutScope(fn () => Theme::withoutGlobalScopes()->where('slug', $this->argument('slug'))->first());

        if (! $theme) {
            $this->error("No theme with slug \"{$this->argument('slug')}\".");

            return self::FAILURE;
        }

        $dir = $this->option('dir') ?: resource_path('views/Themes/'.$theme->slug);

        if ($this->option('export') === $this->option('import')) {
            $this->error('Pass exactly one of --export or --import.');

            return self::FAILURE;
        }

        return $this->option('export') ? $this->export($theme, $dir) : $this->import($theme, $dir);
    }

    private function export(Theme $theme, string $dir): int
    {
        File::ensureDirectoryExists($dir);
        $count = 0;

        foreach ($theme->files ?? [] as $key => $content) {
            $path = $dir.'/'.(Theme::EDITABLE_FILES[$key] ?? "{$key}.blade.php");
            File::ensureDirectoryExists(dirname($path));
            File::put($path, $content);
            $count++;
        }

        $this->info("Exported {$count} pages from \"{$theme->name}\" to {$dir}");

        return self::SUCCESS;
    }

    private function import(Theme $theme, string $dir): int
    {
        if (! File::isDirectory($dir)) {
            $this->error("Missing {$dir}");

            return self::FAILURE;
        }

        $keyByFilename = array_flip(Theme::EDITABLE_FILES);
        $files = $theme->files ?? [];
        $count = 0;

        foreach (File::allFiles($dir) as $file) {
            $relative = str_replace(DIRECTORY_SEPARATOR, '/', $file->getRelativePathname());

            if (! isset($keyByFilename[$relative])) {
                $this->warn("skipped (not an editable page): {$relative}");
                continue;
            }

            $files[$keyByFilename[$relative]] = File::get($file->getPathname());
            $count++;
        }

        Region::withoutScope(fn () => $theme->update(['files' => $files]));
        $theme->flushViews();

        $this->info("Imported {$count} pages into \"{$theme->name}\"");

        return self::SUCCESS;
    }
}
