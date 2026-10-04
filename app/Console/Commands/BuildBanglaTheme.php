<?php

namespace App\Console\Commands;

use App\Models\Theme;
use App\Support\Region;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

/**
 * Builds (or refreshes) the Bangladesh theme from the Bangla blade files kept in
 * resources/views/Themes/bn. Those files are the reviewable source in git; the theme
 * record is what the site renders and what the admin theme editor edits afterwards.
 */
class BuildBanglaTheme extends Command
{
    protected $signature = 'theme:bangla {--activate : Make it the live theme for Bangladesh}';

    protected $description = 'Create or update the Bangladesh theme from resources/views/Themes/bn';

    public function handle(): int
    {
        $dir = resource_path('views/Themes/bn');

        if (! File::isDirectory($dir)) {
            $this->error("Missing {$dir}");

            return self::FAILURE;
        }

        // filename ("wizard/category.blade.php") -> theme key ("wizard.category")
        $keyByFilename = array_flip(Theme::EDITABLE_FILES);
        $files = [];
        $skipped = [];

        foreach (File::allFiles($dir) as $file) {
            $relative = str_replace(DIRECTORY_SEPARATOR, '/', $file->getRelativePathname());

            if (! isset($keyByFilename[$relative])) {
                $skipped[] = $relative;
                continue;
            }

            $files[$keyByFilename[$relative]] = File::get($file->getPathname());
        }

        $theme = Region::withoutScope(function () use ($files) {
            $theme = Theme::withoutGlobalScopes()
                ->where('region', Region::BD)
                ->where('slug', 'bangla')
                ->first();

            if ($theme) {
                $theme->update(['files' => $files]);

                return $theme;
            }

            return Theme::create([
                'name' => 'Bangla',
                'slug' => 'bangla',
                'description' => 'Bangladesh site - Bangla copy',
                'is_active' => false,
                'files' => $files,
                'region' => Region::BD,
            ]);
        });

        // Cached blade files are written from the stored content, so clear them.
        $theme->flushViews();

        $this->info(count($files)." files written to the \"{$theme->name}\" theme (region: ".Region::BD.").");

        foreach ($skipped as $name) {
            $this->warn("skipped (not an editable theme file): {$name}");
        }

        if ($this->option('activate')) {
            Region::withoutScope(fn () => $theme->activate());
            $this->info('Activated for Bangladesh.');
        }

        return self::SUCCESS;
    }
}
