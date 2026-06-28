<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SiteSettingController extends Controller
{
    public function edit(): View
    {
        $settings = SiteSetting::current();

        return view('admin.settings.edit', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            // Header
            'nav_items' => ['nullable', 'array'],
            'nav_items.*.label' => ['nullable', 'string', 'max:255'],
            'nav_items.*.url' => ['nullable', 'string', 'max:255'],
            'show_nav_cta' => ['sometimes', 'boolean'],
            'nav_cta_label' => ['nullable', 'string', 'max:50'],
            'nav_cta_url' => ['nullable', 'string', 'max:255'],
            'notification_email' => ['nullable', 'email', 'max:255'],
            // Stats
            'stats_items' => ['nullable', 'array'],
            'stats_items.*.value' => ['nullable', 'string', 'max:50'],
            'stats_items.*.label' => ['nullable', 'string', 'max:255'],
            // Section content
            'section_content' => ['nullable', 'array'],
            'section_content.*.subtitle' => ['nullable', 'string', 'max:255'],
            'section_content.*.title' => ['nullable', 'string', 'max:255'],
            'section_content.*.empty' => ['nullable', 'string', 'max:255'],
            // Hero content
            'hero_content' => ['nullable', 'array'],
            'hero_content.greeting' => ['nullable', 'string', 'max:255'],
            'hero_content.download_cv' => ['nullable', 'string', 'max:50'],
            'hero_content.contact_me' => ['nullable', 'string', 'max:50'],
            'hero_content.typed_titles' => ['nullable', 'string', 'max:500'],
            // Contact content
            'contact_content' => ['nullable', 'array'],
            'contact_content.heading' => ['nullable', 'string', 'max:255'],
            'contact_content.description' => ['nullable', 'string', 'max:1000'],
            'contact_content.send_message' => ['nullable', 'string', 'max:50'],
            'contact_content.name' => ['nullable', 'string', 'max:100'],
            'contact_content.email' => ['nullable', 'string', 'max:100'],
            'contact_content.subject' => ['nullable', 'string', 'max:100'],
            'contact_content.phone' => ['nullable', 'string', 'max:100'],
            'contact_content.message' => ['nullable', 'string', 'max:100'],
            // Labels
            'labels' => ['nullable', 'array'],
            'labels.present' => ['nullable', 'string', 'max:50'],
            'labels.current' => ['nullable', 'string', 'max:50'],
            'labels.featured' => ['nullable', 'string', 'max:50'],
            // Admin branding
            'admin_branding' => ['nullable', 'array'],
            'admin_branding.title' => ['nullable', 'string', 'max:255'],
            'admin_branding.mark' => ['nullable', 'string', 'max:10'],
            'admin_branding.panel_name' => ['nullable', 'string', 'max:100'],
            // Favicon
            'favicon' => ['nullable', 'image', 'mimes:ico,png,jpg,jpeg,gif,webp', 'max:512'],
            'remove_favicon' => ['nullable', 'boolean'],
            'default_theme' => ['nullable', 'string', 'in:light,dark,system'],
            // Footer
            'footer_about' => ['nullable', 'string', 'max:1000'],
            'footer_copyright' => ['nullable', 'string', 'max:255'],
            'footer_nav_items' => ['nullable', 'array'],
            'footer_nav_items.*.label' => ['nullable', 'string', 'max:255'],
            'footer_nav_items.*.url' => ['nullable', 'string', 'max:255'],
            'footer_social_facebook' => ['nullable', 'string', 'max:255'],
            'footer_social_linkedin' => ['nullable', 'string', 'max:255'],
            'footer_social_github' => ['nullable', 'string', 'max:255'],
            'footer_social_twitter' => ['nullable', 'string', 'max:255'],
            // SEO
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'meta_keywords' => ['nullable', 'string', 'max:500'],
        ]);

        $validated['show_nav_cta'] = $request->boolean('show_nav_cta');

        $validated['nav_items'] = $this->buildPairs($request->input('nav_items'));
        $validated['footer_nav_items'] = $this->buildPairs($request->input('footer_nav_items'));
        $validated['stats_items'] = $this->buildStats($request->input('stats_items'));

        $settings = SiteSetting::current();

        // Favicon upload / removal
        if ($request->hasFile('favicon')) {
            if ($settings->favicon) {
                Storage::disk('public')->delete($settings->favicon);
            }
            $validated['favicon'] = $this->storeWithOriginalName($request->file('favicon'), 'favicon');
        } elseif ($request->boolean('remove_favicon') && $settings->favicon) {
            Storage::disk('public')->delete($settings->favicon);
            $validated['favicon'] = null;
        }

        unset($validated['remove_favicon']);

        $settings->update($validated);

        return redirect()
            ->route('admin.settings.edit')
            ->with('success', 'Site settings updated successfully.');
    }

    private function buildPairs(?array $input): array
    {
        if (empty($input)) {
            return [];
        }

        $pairs = [];

        foreach ($input as $entry) {
            if (! is_array($entry)) {
                continue;
            }

            $label = trim((string) ($entry['label'] ?? ''));
            $url = trim((string) ($entry['url'] ?? ''));

            if ($label !== '' && $url !== '') {
                $pairs[] = ['label' => $label, 'url' => $url];
            }
        }

        return $pairs;
    }

    private function buildStats(?array $input): array
    {
        if (empty($input)) {
            return [];
        }

        $stats = [];

        foreach ($input as $entry) {
            if (! is_array($entry)) {
                continue;
            }

            $value = trim((string) ($entry['value'] ?? ''));
            $label = trim((string) ($entry['label'] ?? ''));

            if ($value !== '' && $label !== '') {
                $stats[] = ['value' => $value, 'label' => $label];
            }
        }

        return $stats;
    }
}
