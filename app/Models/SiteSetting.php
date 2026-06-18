<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use HasFactory;

    protected $table = 'site_settings';

    protected $fillable = [
        // Header
        'nav_items',
        'show_nav_cta',
        'nav_cta_label',
        'nav_cta_url',
        'notification_email',
        'stats_items',
        // Dynamic content groups
        'section_content',
        'hero_content',
        'contact_content',
        'labels',
        'admin_branding',
        'favicon',
        'default_theme',
        // Footer
        'footer_about',
        'footer_copyright',
        'footer_nav_items',
        'footer_social_facebook',
        'footer_social_linkedin',
        'footer_social_github',
        'footer_social_twitter',
        // SEO
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected $casts = [
        'nav_items' => 'array',
        'footer_nav_items' => 'array',
        'stats_items' => 'array',
        'section_content' => 'array',
        'hero_content' => 'array',
        'contact_content' => 'array',
        'labels' => 'array',
        'admin_branding' => 'array',
        'show_nav_cta' => 'boolean',
    ];

    /*
    |----------------------------------------------------------------------
    | Defaults — used when a JSON key or the whole row is missing so the
    | app never crashes and always shows sensible content.
    |----------------------------------------------------------------------
    */

    /** @var array<string, array{subtitle: string, title: string, empty: string}> */
    private const DEFAULT_SECTIONS = [
        'about'      => ['subtitle' => 'Get To Know Me', 'title' => 'About Me', 'empty' => ''],
        'skills'     => ['subtitle' => 'What I Know', 'title' => 'My Skills', 'empty' => 'Skills coming soon.'],
        'experience' => ['subtitle' => 'My Journey', 'title' => 'Work Experience', 'empty' => 'Experience details coming soon.'],
        'education'  => ['subtitle' => 'My Background', 'title' => 'Education', 'empty' => 'Education details coming soon.'],
        'projects'   => ['subtitle' => 'My Work', 'title' => 'Featured Projects', 'empty' => 'Projects coming soon.'],
        'training'   => ['subtitle' => 'My Certifications', 'title' => 'Training & Certifications', 'empty' => 'Training details coming soon.'],
        'contact'    => ['subtitle' => 'Get In Touch', 'title' => 'Contact Me', 'empty' => ''],
    ];

    /** @var array<string, string> */
    private const DEFAULT_HERO = [
        'greeting'     => "Hello, I'm",
        'download_cv'  => 'Download CV',
        'contact_me'   => 'Contact Me',
        'typed_titles' => 'Full Stack Developer, Software Engineer',
    ];

    /** @var array<string, string> */
    private const DEFAULT_CONTACT = [
        'heading'      => "Let's Talk About Your Project",
        'description'  => "Feel free to reach out for collaborations or just a friendly hello. I'm always open to discussing new projects, creative ideas, or opportunities to be part of your vision.",
        'send_message' => 'Send Message',
        'name'         => 'Your Name',
        'email'        => 'Your Email',
        'subject'      => 'Subject',
        'phone'        => 'Phone (optional)',
        'message'      => 'Your Message',
    ];

    /** @var array<string, string> */
    private const DEFAULT_LABELS = [
        'present'  => 'Present',
        'current'  => 'Current',
        'featured' => 'Featured',
    ];

    /** @var array<string, string> */
    private const DEFAULT_BRANDING = [
        'title'      => 'Rasel Bepari',
        'mark'       => 'RB',
        'panel_name' => 'Admin Panel',
    ];

    /**
     * Return the single site-settings record.
     */
    public static function current(): self
    {
        return self::first() ?? self::create([]);
    }

    // ── Section content ──────────────────────────────────────────────

    /**
     * Get a merged view of section content (DB + defaults).
     *
     * @return array<string, array{subtitle: string, title: string, empty: string}>
     */
    public function sections(): array
    {
        $stored = is_array($this->section_content) ? $this->section_content : [];

        $merged = [];
        foreach (self::DEFAULT_SECTIONS as $key => $defaults) {
            $storedVals = $stored[$key] ?? [];
            $merged[$key] = [
                'subtitle' => $storedVals['subtitle'] ?? $defaults['subtitle'],
                'title'    => $storedVals['title'] ?? $defaults['title'],
                'empty'    => $storedVals['empty'] ?? $defaults['empty'],
            ];
        }

        return $merged;
    }

    // ── Hero content ─────────────────────────────────────────────────

    /**
     * @return array<string, string>
     */
    public function hero(): array
    {
        $stored = is_array($this->hero_content) ? $this->hero_content : [];

        return array_merge(self::DEFAULT_HERO, $stored);
    }

    /**
     * Typed-rotation titles for the hero, as an ordered list.
     *
     * @return array<int, string>
     */
    public function typedTitles(?string $profileTitle = null, ?string $profileTagline = null): array
    {
        $hero = $this->hero();

        $titles = array_filter([
            $profileTitle ?? '',
            $profileTagline ?? '',
        ]);

        if (! empty($hero['typed_titles'])) {
            foreach (explode(',', $hero['typed_titles']) as $t) {
                $t = trim($t);
                if ($t !== '') {
                    $titles[] = $t;
                }
            }
        }

        return array_values(array_unique($titles));
    }

    // ── Contact content ──────────────────────────────────────────────

    /**
     * @return array<string, string>
     */
    public function contact(): array
    {
        $stored = is_array($this->contact_content) ? $this->contact_content : [];

        return array_merge(self::DEFAULT_CONTACT, $stored);
    }

    // ── Labels ───────────────────────────────────────────────────────

    /**
     * @return array<string, string>
     */
    public function labels(): array
    {
        $stored = is_array($this->labels) ? $this->labels : [];

        return array_merge(self::DEFAULT_LABELS, $stored);
    }

    /**
     * Convenience getter for a single label.
     */
    public function label(string $key): string
    {
        return $this->labels()[$key] ?? ucfirst($key);
    }

    // ── Admin branding ───────────────────────────────────────────────

    /**
     * @return array<string, string>
     */
    public function branding(): array
    {
        $stored = is_array($this->admin_branding) ? $this->admin_branding : [];

        return array_merge(self::DEFAULT_BRANDING, $stored);
    }

    // ── Nav links ────────────────────────────────────────────────────

    /**
     * @return array<int, array{label: string, url: string}>
     */
    public function navItems(): array
    {
        return $this->parseLinkItems($this->nav_items);
    }

    /**
     * @return array<int, array{label: string, url: string}>
     */
    public function footerNavItems(): array
    {
        return $this->parseLinkItems($this->footer_nav_items);
    }

    /**
     * @return array<int, array{label: string, url: string}>
     */
    private function parseLinkItems(mixed $items): array
    {
        if (empty($items) || ! is_array($items)) {
            return [];
        }

        $result = [];

        foreach ($items as $key => $value) {
            if (is_array($value)) {
                $label = trim((string) ($value['label'] ?? ''));
                $url = trim((string) ($value['url'] ?? ''));
            } else {
                $label = trim((string) $key);
                $url = trim((string) $value);
            }

            if ($label !== '' && $url !== '') {
                $result[] = ['label' => $label, 'url' => $url];
            }
        }

        return $result;
    }

    // ── Footer ───────────────────────────────────────────────────────

    /**
     * @return array<string, string>
     */
    public function footerSocials(): array
    {
        return array_filter([
            'facebook' => $this->footer_social_facebook,
            'linkedin' => $this->footer_social_linkedin,
            'github' => $this->footer_social_github,
            'twitter' => $this->footer_social_twitter,
        ], fn ($value) => ! empty($value));
    }

    public function renderedCopyright(string $fallbackName): string
    {
        $text = $this->footer_copyright ?: '© {year} {name}. All rights reserved.';

        return str_replace(
            ['{year}', '{name}'],
            [(string) now()->year, $fallbackName],
            $text
        );
    }

    // ── Favicon ──────────────────────────────────────────────────────

    /**
     * Resolve the favicon URL, falling back to the profile logo if
     * no dedicated favicon is set.
     */
    public function faviconUrl(): ?string
    {
        if ($this->favicon && \Illuminate\Support\Facades\Storage::disk('public')->exists($this->favicon)) {
            return asset('storage/' . $this->favicon);
        }

        $logo = Profile::first()?->logo;

        if ($logo && \Illuminate\Support\Facades\Storage::disk('public')->exists($logo)) {
            return asset('storage/' . $logo);
        }

        return null;
    }

    // ── Email ────────────────────────────────────────────────────────

    public function notificationRecipient(): ?string
    {
        if (! empty($this->notification_email)) {
            return $this->notification_email;
        }

        return Profile::first()?->email;
    }

    // ── Stats ────────────────────────────────────────────────────────

    /**
     * @return array<int, array{value: int, label: string}>
     */
    public function parsedStats(): array
    {
        if (empty($this->stats_items) || ! is_array($this->stats_items)) {
            return [];
        }

        $result = [];

        foreach ($this->stats_items as $stat) {
            if (! is_array($stat)) {
                continue;
            }

            $rawValue = trim((string) ($stat['value'] ?? ''));
            $label = trim((string) ($stat['label'] ?? ''));

            if ($label === '') {
                continue;
            }

            $value = $this->resolveStatValue($rawValue);

            if ($value !== null) {
                $result[] = ['value' => $value, 'label' => $label];
            }
        }

        return $result;
    }

    private function resolveStatValue(string $raw): ?int
    {
        return match ($raw) {
            '{experience_count}' => Experience::count(),
            '{project_count}' => Project::count(),
            '{skill_count}' => Skill::count(),
            default => is_numeric($raw) ? (int) $raw : null,
        };
    }
}
