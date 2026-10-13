<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;

/**
 * Marks a page's wording as client-editable: each listed string is swapped for a
 * content() call with the original text as its fallback, in every language copy.
 *
 * The map is JSON:
 *   [{"key": "hero_title", "type": "text|html", "uk": "English text", "bn": "বাংলা"}]
 *
 * Replacement goes through placeholders, longest string first, so a phrase that is a
 * substring of another can never land inside an already-converted call.
 */
class ConvertPageContent extends Command
{
    protected $signature = 'content:convert {page} {--map= : JSON file of fields} {--dirs=uk,bn}';

    protected $description = 'Replace hardcoded page wording with editable content() fields';

    public function handle(): int
    {
        $page = $this->argument('page');
        $mapPath = $this->option('map');

        if (! $mapPath || ! File::exists($mapPath)) {
            $this->error('Pass --map=<file.json> with the fields to convert.');

            return self::FAILURE;
        }

        $fields = json_decode(File::get($mapPath), true);

        if (! is_array($fields) || $fields === []) {
            $this->error('The map file is empty or not valid JSON.');

            return self::FAILURE;
        }

        $failed = false;

        foreach (explode(',', $this->option('dirs')) as $dir) {
            $dir = trim($dir);
            $path = $this->pagePath($dir, $page);

            if (! $path) {
                $this->warn("{$dir}: no file for page \"{$page}\", skipped");
                continue;
            }

            $failed = ! $this->convertFile($path, $page, $fields, $dir) || $failed;
        }

        return $failed ? self::FAILURE : self::SUCCESS;
    }

    private function pagePath(string $dir, string $page): ?string
    {
        $filename = \App\Models\Theme::EDITABLE_FILES[$page] ?? null;

        if (! $filename) {
            return null;
        }

        $path = resource_path("views/Themes/{$dir}/{$filename}");

        return File::exists($path) ? $path : null;
    }

    private function convertFile(string $path, string $page, array $fields, string $language): bool
    {
        $result = \App\Support\ContentConverter::apply(File::get($path), $page, $fields, $language);

        if ($result['error']) {
            $this->error("{$language}/{$page}: {$result['error']} - left unchanged");

            return false;
        }

        File::put($path, $result['source']);

        $note = $result['missing'] ? ' | not found: '.implode(', ', $result['missing']) : '';
        $this->info("{$language}/{$page}: {$result['converted']} field(s) converted".$note);

        return true;
    }

}
