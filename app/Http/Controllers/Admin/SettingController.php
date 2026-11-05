<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function edit(): View
    {
        $keys = [
            'site_name', 'site_tagline', 'support_email',
            'seo_title_suffix', 'seo_default_description',
            'default_locale', 'default_currency', 'usd_to_eur_rate',
        ];

        $settings = [];
        foreach ($keys as $key) {
            $settings[$key] = Setting::getValue($key, '');
        }

        return view('admin.settings.edit', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'site_name' => ['required', 'string', 'max:120'],
            'site_tagline' => ['nullable', 'string', 'max:255'],
            'support_email' => ['nullable', 'email'],
            'seo_title_suffix' => ['nullable', 'string', 'max:80'],
            'seo_default_description' => ['nullable', 'string', 'max:500'],
            'default_locale' => ['required', 'in:en,ru'],
            'default_currency' => ['required', 'in:USD,EUR'],
            'usd_to_eur_rate' => ['required', 'numeric', 'min:0.01'],
        ]);

        foreach ($data as $key => $value) {
            $group = str_starts_with($key, 'seo_') ? 'seo' : (in_array($key, ['default_locale', 'default_currency', 'usd_to_eur_rate'], true) ? 'i18n' : 'general');
            Setting::setValue($key, $value, 'string', $group);
        }

        return back()->with('status', 'Settings saved.');
    }
}
