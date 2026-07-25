<?php

namespace App\Http\Controllers;

use App\Models\Theme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Inertia\Inertia;

class ThemeController extends Controller
{
    /**
     * Display a listing of all themes.
     */
    public function index()
    {
        $themes = Theme::latest()->get();

        return Inertia::render('themes/index', [
            'themes' => $themes,
        ]);
    }

    /**
     * Show the form for creating a new theme.
     */
    public function create()
    {
        return Inertia::render('themes/create', [
            'defaultFiles' => Theme::getAllDefaults(),
            'fileKeys' => Theme::EDITABLE_FILES,
            'fileLabels' => Theme::FILE_LABELS,
        ]);
    }

    /**
     * Store a newly created theme in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $theme = Theme::create([
            'name' => $validated['name'],
            'slug' => Theme::generateSlug($validated['name']),
            'description' => $validated['description'] ?? null,
            'is_active' => false,
            'files' => Theme::getAllDefaults(),
        ]);

        return redirect()->route('themes.edit', $theme)
            ->with('success', 'Theme created successfully. You can now edit the files.');
    }

    /**
     * Redirect to the edit page.
     */
    public function show(Theme $theme)
    {
        return redirect()->route('themes.edit', $theme);
    }

    /**
     * Show the Monaco editor for editing theme files.
     */
    public function edit(Theme $theme)
    {
        return Inertia::render('themes/edit', [
            'theme' => $theme,
            'fileKeys' => Theme::EDITABLE_FILES,
            'fileLabels' => Theme::FILE_LABELS,
            'pageGroups' => Theme::PAGE_GROUPS,
            'previewUrls' => Theme::PAGE_PREVIEW_URLS,
        ]);
    }

    /**
     * Update the theme's general information.
     */
    public function update(Request $request, Theme $theme)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $theme->update($validated);

        return back()->with('success', 'Theme updated successfully.');
    }

    /**
     * Remove the specified theme from storage.
     */
    public function destroy(Theme $theme)
    {
        if ($theme->is_active) {
            return back()->withErrors(['error' => 'Cannot delete the currently active theme. Deactivate it first.']);
        }

        $theme->flushViews();
        $theme->delete();

        return redirect()->route('themes.index')
            ->with('success', 'Theme deleted successfully.');
    }

    /**
     * Activate a theme. Deactivates all other themes.
     */
    public function activate(Theme $theme)
    {
        $theme->activate();
        $theme->flushViews(); // regenerate cached views

        return back()->with('success', "Theme \"{$theme->name}\" is now active.");
    }

    /**
     * Deactivate a theme.
     */
    public function deactivate(Theme $theme)
    {
        $theme->deactivate();
        $theme->flushViews();

        return back()->with('success', "Theme \"{$theme->name}\" has been deactivated.");
    }

    /**
     * API: Get the content of a single file.
     */
    public function getFile(Theme $theme, string $key)
    {
        if (!array_key_exists($key, Theme::EDITABLE_FILES)) {
            return response()->json(['error' => 'Invalid file key'], 404);
        }

        return response()->json([
            'key' => $key,
            'filename' => Theme::EDITABLE_FILES[$key],
            'label' => Theme::FILE_LABELS[$key] ?? $key,
            'content' => $theme->getFileContent($key),
        ]);
    }

    /**
     * API: Save a single file's content.
     */
    public function updateFile(Request $request, Theme $theme, string $key)
    {
        if (!array_key_exists($key, Theme::EDITABLE_FILES)) {
            return response()->json(['error' => 'Invalid file key'], 404);
        }

        $validated = $request->validate([
            'content' => 'required|string',
        ]);

        $theme->setFileContent($key, $validated['content']);

        // If this theme is active, regenerate its cached view
        if ($theme->is_active) {
            $theme->resolveViewPath($key);
        }

        return response()->json([
            'success' => true,
            'message' => 'File saved successfully.',
        ]);
    }
}
