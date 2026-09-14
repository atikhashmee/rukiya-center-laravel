<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class WhatsAppController extends Controller
{
    /**
     * Show the WhatsApp settings page.
     */
    public function edit(): Response
    {
        return Inertia::render('settings/whatsapp', [
            'whatsappNumber' => Setting::get('whatsapp_number'),
        ]);
    }

    /**
     * Update the WhatsApp number. Stored as digits only (wa.me format).
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'whatsapp_number' => ['nullable', 'string', 'regex:/^\+?[0-9\s\-()]{7,20}$/'],
        ]);

        $digits = preg_replace('/\D/', '', $validated['whatsapp_number'] ?? '');

        Setting::set('whatsapp_number', $digits ?: null);

        return back();
    }
}
