<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class Theme extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active',
        'files',
    ];

    protected $casts = [
        'files' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Files that can be edited in the admin panel.
     */
    const EDITABLE_FILES = [
        'index'            => 'index.blade.php',
        'about'            => 'about.blade.php',
        'contact'          => 'contact.blade.php',
        'service'          => 'service.blade.php',
        'free-counselling' => 'free-counselling.blade.php',
        'service-detail'   => 'service-detail.blade.php',
    ];

    /**
     * Human-readable labels for the editable files.
     */
    const FILE_LABELS = [
        'index'            => 'Homepage',
        'about'            => 'About Page',
        'contact'          => 'Contact Page',
        'service'          => 'Services Page',
        'free-counselling' => 'Free Counselling',
        'service-detail'   => 'Service Detail',
    ];

    /**
     * Get the currently active theme.
     */
    public static function active(): ?static
    {
        return static::where('is_active', true)->first();
    }

    /**
     * Scope to query only the active theme.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Set this theme as active (deactivate all others first).
     */
    public function activate(): void
    {
        static::query()->update(['is_active' => false]);
        $this->update(['is_active' => true]);
    }

    /**
     * Deactivate this theme.
     */
    public function deactivate(): void
    {
        $this->update(['is_active' => false]);
    }

    /**
     * Get the default content for a given file key by reading
     * the original blade template from resources/views/Themes/.
     */
    public static function getDefaultContent(string $key): string
    {
        $filename = self::EDITABLE_FILES[$key] ?? null;

        if (!$filename) {
            return '';
        }

        $path = resource_path("views/Themes/{$filename}");

        if (File::exists($path)) {
            return File::get($path);
        }

        return '';
    }

    /**
     * Get all default file contents (key => content).
     */
    public static function getAllDefaults(): array
    {
        $defaults = [];
        foreach (self::EDITABLE_FILES as $key => $filename) {
            $defaults[$key] = self::getDefaultContent($key);
        }
        return $defaults;
    }

    /**
     * Get the content of a specific file from this theme.
     */
    public function getFileContent(string $key): string
    {
        return data_get($this->files, $key, '');
    }

    /**
     * Set the content of a specific file in this theme.
     */
    public function setFileContent(string $key, string $content): void
    {
        $files = $this->files ?? [];
        $files[$key] = $content;
        $this->update(['files' => $files]);
    }

    /**
     * Generate a slug from the name.
     */
    public static function generateSlug(string $name): string
    {
        $slug = Str::slug($name);
        $original = $slug;
        $count = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = $original . '-' . $count;
            $count++;
        }

        return $slug;
    }

    /**
     * Resolve a theme view. If this theme has the file, return its path
     * from storage. Otherwise, return null (use default).
     */
    public function resolveViewPath(string $key): ?string
    {
        $content = $this->getFileContent($key);

        if (empty($content)) {
            return null;
        }

        $storagePath = storage_path("app/themes/{$this->slug}");

        if (!is_dir($storagePath)) {
            mkdir($storagePath, 0755, true);
        }

        $filename = self::EDITABLE_FILES[$key] ?? "{$key}.blade.php";
        $filePath = "{$storagePath}/{$filename}";

        File::put($filePath, $content);

        return $filePath;
    }

    /**
     * Flush all cached theme views from storage.
     */
    public function flushViews(): void
    {
        $dir = storage_path("app/themes/{$this->slug}");

        if (is_dir($dir)) {
            File::deleteDirectory($dir);
        }
    }
}
