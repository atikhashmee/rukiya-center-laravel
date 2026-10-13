<?php

namespace App\Http\Controllers;

use App\Models\PageContent;
use App\Models\Theme;
use App\Support\ContentEditMode;
use App\Support\ContentSchema;
use App\Support\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

/**
 * The client-facing way to edit the website: labelled fields per page instead of
 * blade code. Fields are discovered from the page templates themselves
 * (see ContentSchema), and values are stored per region.
 */
class PageContentController extends Controller
{
    public function index()
    {
        $region = Region::current() ?? Region::UK;

        $pages = collect(Theme::EDITABLE_FILES)
            ->map(function ($filename, $key) use ($region) {
                $fields = ContentSchema::forPage($key, $region);

                return [
                    'key' => $key,
                    'label' => Theme::FILE_LABELS[$key] ?? $key,
                    'fields' => count($fields),
                    'edited' => PageContent::where('region', $region)->where('page', $key)->count(),
                    'group' => collect(Theme::PAGE_GROUPS)->search(fn ($keys) => in_array($key, $keys, true)) ?: 'Other',
                ];
            })
            ->values()
            ->all();

        return Inertia::render('content/index', [
            'pages' => $pages,
            'regionLabel' => Region::label($region),
        ]);
    }

    public function edit(string $page)
    {
        abort_unless(array_key_exists($page, Theme::EDITABLE_FILES), 404);

        $region = Region::current() ?? Region::UK;
        $saved = PageContent::forPage($page, $region);

        $fields = collect(ContentSchema::forPage($page, $region))
            ->map(fn ($field) => [
                ...$field,
                'value' => $saved[$field['key']] ?? null,
            ])
            ->all();

        return Inertia::render('content/edit', [
            'page' => ['key' => $page, 'label' => Theme::FILE_LABELS[$page] ?? $page],
            'fields' => $fields,
            'previewUrl' => Theme::PAGE_PREVIEW_URLS[$page] ?? null,
            'regionLabel' => Region::label($region),
        ]);
    }

    /**
     * The visual editor: the real page in a frame, with its text editable in place.
     * The preview carries a short-lived token because the Bangladesh site is a
     * different host and will not receive the admin's session cookie.
     */
    public function visual(string $page)
    {
        abort_unless(array_key_exists($page, Theme::EDITABLE_FILES), 404);

        $region = Region::current() ?? Region::UK;
        $path = Theme::PAGE_PREVIEW_URLS[$page] ?? null;

        $previewUrl = $path
            ? Region::url($region, $path).'?'.ContentEditMode::PARAM.'='.ContentEditMode::issueToken($page)
            : null;

        $saved = PageContent::forPage($page, $region);

        return Inertia::render('content/visual', [
            'page' => ['key' => $page, 'label' => Theme::FILE_LABELS[$page] ?? $page],
            'previewUrl' => $previewUrl,
            'fields' => collect(ContentSchema::forPage($page, $region))
                ->map(fn ($field) => [...$field, 'value' => $saved[$field['key']] ?? null])
                ->all(),
            'regionLabel' => Region::label($region),
        ]);
    }

    public function update(Request $request, string $page)
    {
        abort_unless(array_key_exists($page, Theme::EDITABLE_FILES), 404);

        // Base64 for the same reason as the theme editor: firewalls reject raw HTML bodies.
        $validated = $request->validate(['values_b64' => 'required|string']);

        $decoded = base64_decode($validated['values_b64'], true);
        $values = $decoded === false ? null : json_decode($decoded, true);

        if (! is_array($values)) {
            return back()->with('error', 'The submitted content could not be read.');
        }

        $region = Region::current() ?? Region::UK;
        $allowed = collect(ContentSchema::forPage($page, $region))->pluck('key')->all();

        foreach ($values as $key => $value) {
            if (in_array($key, $allowed, true)) {
                PageContent::put($page, (string) $key, $value === null ? null : (string) $value, $region);
            }
        }

        return back()->with('success', 'Page content saved.');
    }

    /** Replace one image field; returns the stored URL for the form to show. */
    public function uploadImage(Request $request, string $page, string $key)
    {
        abort_unless(array_key_exists($page, Theme::EDITABLE_FILES), 404);

        $request->validate(['image' => 'required|image|mimes:jpg,jpeg,png,webp,gif|max:4096']);

        $region = Region::current() ?? Region::UK;

        abort_unless(
            collect(ContentSchema::forPage($page, $region))
                ->contains(fn ($field) => $field['key'] === $key && $field['type'] === 'image'),
            404,
        );

        $old = PageContent::forPage($page, $region)[$key] ?? null;

        if ($old && Str::startsWith($old, '/storage/content/')) {
            Storage::disk('public')->delete(ltrim(Str::after($old, '/storage/'), '/'));
        }

        $url = Storage::url($request->file('image')->store('content', 'public'));
        PageContent::put($page, $key, $url, $region);

        return back()->with('success', 'Image updated.');
    }

    /** Put one field back to the template's own wording. */
    public function reset(Request $request, string $page)
    {
        $request->validate(['key' => 'required|string']);

        $region = Region::current() ?? Region::UK;

        PageContent::where('region', $region)
            ->where('page', $page)
            ->where('key', $request->key)
            ->delete();

        return back()->with('success', 'Reset to the original wording.');
    }
}
