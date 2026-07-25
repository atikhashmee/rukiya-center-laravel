<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
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
     * Keys with a dot (e.g. "wizard.category") map to a nested blade view
     * (resources/views/Themes/wizard/category.blade.php) - same convention Blade itself uses.
     */
    const EDITABLE_FILES = [
        // Core Pages
        'index' => 'index.blade.php',
        'about' => 'about.blade.php',
        'contact' => 'contact.blade.php',
        'free-counselling' => 'free-counselling.blade.php',

        // Shop
        'shop' => 'shop.blade.php',
        'shop-show' => 'shop-show.blade.php',
        'cart' => 'cart.blade.php',
        'checkout-form' => 'checkout-form.blade.php',

        // Booking & Services
        'service-detail' => 'service-detail.blade.php',
        'service-book-preview' => 'service-book-preview.blade.php',
        'checkout' => 'checkout.blade.php',
        'payment-result-page' => 'payment-result-page.blade.php',
        'booking-confirm' => 'booking-confirm.blade.php',
        'booking-failed' => 'booking-failed.blade.php',
        'booking-pending' => 'booking-pending.blade.php',

        // Booking Wizard
        'wizard.category' => 'wizard/category.blade.php',
        'wizard.service' => 'wizard/service.blade.php',
        'wizard.instructor' => 'wizard/instructor.blade.php',
        'wizard.schedule' => 'wizard/schedule.blade.php',
        'wizard.confirm' => 'wizard/confirm.blade.php',
        'wizard.completed' => 'wizard/completed.blade.php',
        'wizard.pending' => 'wizard/pending.blade.php',

        // Blog
        'blog' => 'blog.blade.php',
        'blog-show' => 'blog-show.blade.php',

        // Customer Account
        'login' => 'login.blade.php',
        'register' => 'register.blade.php',
        'profile' => 'profile.blade.php',
        'customer.my-booking' => 'customer/my-booking.blade.php',
        'customer.my-transactions' => 'customer/my-transactions.blade.php',
        'auth.verify-email-page' => 'auth/verify-email-page.blade.php',
    ];

    /**
     * Human-readable labels for the editable files.
     */
    const FILE_LABELS = [
        'index' => 'Homepage',
        'about' => 'About Page',
        'contact' => 'Contact Page',
        'free-counselling' => 'Free Counselling',

        'shop' => 'Shop Listing',
        'shop-show' => 'Product Detail',
        'cart' => 'Shopping Cart',
        'checkout-form' => 'Checkout Form',

        'service-detail' => 'Service Detail',
        'service-book-preview' => 'Booking Preview',
        'checkout' => 'Payment Checkout',
        'payment-result-page' => 'Payment Result',
        'booking-confirm' => 'Booking Confirmed',
        'booking-failed' => 'Booking Failed',
        'booking-pending' => 'Booking Pending',

        'wizard.category' => 'Category',
        'wizard.service' => 'Service',
        'wizard.instructor' => 'Instructor',
        'wizard.schedule' => 'Schedule',
        'wizard.confirm' => 'Confirm',
        'wizard.completed' => 'Completed',
        'wizard.pending' => 'Pending',

        'blog' => 'Blog Listing',
        'blog-show' => 'Blog Post',

        'login' => 'Customer Login',
        'register' => 'Customer Register',
        'profile' => 'Customer Profile',
        'customer.my-booking' => 'My Bookings',
        'customer.my-transactions' => 'My Transactions',
        'auth.verify-email-page' => 'Verify Email',
    ];

    /**
     * Groups pages for the admin editor's sidebar. Purely organizational -
     * EDITABLE_FILES/FILE_LABELS above remain the source of truth for validity.
     */
    const PAGE_GROUPS = [
        'Core Pages' => ['index', 'about', 'contact', 'free-counselling'],
        'Shop' => ['shop', 'shop-show', 'cart', 'checkout-form'],
        'Booking & Services' => [
            'service-detail', 'service-book-preview', 'checkout', 'payment-result-page',
            'booking-confirm', 'booking-failed', 'booking-pending',
        ],
        'Booking Wizard' => [
            'wizard.category', 'wizard.service', 'wizard.instructor', 'wizard.schedule',
            'wizard.confirm', 'wizard.completed', 'wizard.pending',
        ],
        'Blog' => ['blog', 'blog-show'],
        'Customer Account' => [
            'login', 'register', 'profile', 'customer.my-booking',
            'customer.my-transactions', 'auth.verify-email-page',
        ],
    ];

    /**
     * Live, parameter-free URLs for pages that can be previewed directly.
     * Pages needing a record id (a product, a service, a booking) are omitted.
     */
    const PAGE_PREVIEW_URLS = [
        'index' => '/',
        'about' => '/about',
        'contact' => '/contact',
        'free-counselling' => '/free-counselling',
        'shop' => '/shop',
        'cart' => '/cart',
        'blog' => '/blog',
        'login' => '/customer/login',
        'register' => '/customer/register',
        'wizard.category' => '/book',
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
        // Plain array-key lookup, not data_get(): some keys (e.g. "wizard.category")
        // contain a literal dot, which data_get() would misread as nested-array traversal.
        return ($this->files ?? [])[$key] ?? '';
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

        $filename = self::EDITABLE_FILES[$key] ?? "{$key}.blade.php";
        $filePath = "{$storagePath}/{$filename}";

        // Nested keys (e.g. "wizard.category" -> wizard/category.blade.php) need their
        // subdirectory created too, not just the theme root.
        $fileDir = dirname($filePath);
        if (!is_dir($fileDir)) {
            mkdir($fileDir, 0755, true);
        }

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

    /**
     * Resolve the view name to render for a given page key: the active theme's
     * override if it has custom content saved for that key, otherwise the
     * default static blade view. This is the single entry point every
     * controller/route should call instead of hardcoding view('Themes.xxx').
     */
    public static function resolveViewName(string $key): string
    {
        static $prependedDirs = [];

        $theme = static::active();

        if ($theme) {
            $path = $theme->resolveViewPath($key);

            if ($path) {
                $dir = dirname($path);
                if (!in_array($dir, $prependedDirs, true)) {
                    View::prependLocation($dir);
                    $prependedDirs[] = $dir;
                }

                return basename($path, '.blade.php');
            }
        }

        return "Themes.{$key}";
    }
}
