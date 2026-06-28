@extends('admin.layout', ['title' => 'Site Settings', 'subtitle' => 'Customize the public header, footer and SEO.'])

@section('content')

    <div class="content--narrow">

        <div class="page-head">
            <div>
                <h2>Site Settings</h2>
                <p>These values drive the public site header, footer and meta tags.</p>
            </div>
            <div class="page-head__actions">
                <a href="{{ route('admin.dashboard') }}" class="btn btn--outline">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                    Back to Dashboard
                </a>
            </div>
        </div>

        <form id="settingsForm" method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- ===================== HEADER / NAVIGATION ===================== --}}
            <div class="card section">
                <div class="card__head">
                    <div>
                        <h3>Header &amp; Navigation</h3>
                        <p>Manage the links shown in the public site navbar.</p>
                    </div>
                </div>
                <div class="card__body">
                    <div class="field field--full">
                        <label class="field__label">Navigation Links</label>
                        <span class="field__hint">Each link needs a label and a URL. Use <code>#section</code> for in-page anchors (e.g. <code>#about</code>). Blank rows are ignored. Drag <svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:middle;"><circle cx="9" cy="6" r="1"/><circle cx="9" cy="12" r="1"/><circle cx="9" cy="18" r="1"/><circle cx="15" cy="6" r="1"/><circle cx="15" cy="12" r="1"/><circle cx="15" cy="18" r="1"/></svg> to reorder.</span>

                        <div id="navRows" class="pair-rows sortable-list">
                            <?php
                                $navItems = old('nav_items') ?: $settings->navItems();
                                if (empty($navItems)) {
                                    $navItems = [
                                        ['label' => 'Home', 'url' => '#home'],
                                        ['label' => 'About', 'url' => '#about'],
                                        ['label' => 'Contact', 'url' => '#contact'],
                                    ];
                                }
                            ?>
                            @foreach ($navItems as $item)
                                <div class="pair-row">
                                    <span class="drag-handle" aria-label="Drag to reorder" title="Drag to reorder"><svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="6" r="1"/><circle cx="9" cy="12" r="1"/><circle cx="9" cy="18" r="1"/><circle cx="15" cy="6" r="1"/><circle cx="15" cy="12" r="1"/><circle cx="15" cy="18" r="1"/></svg></span>
                                    <input class="input pair-label" type="text" value="{{ is_array($item) ? ($item['label'] ?? '') : $item }}" placeholder="Label">
                                    <input class="input pair-url" type="text" value="{{ is_array($item) ? ($item['url'] ?? '') : '' }}" placeholder="URL / #anchor">
                                    <button type="button" class="btn btn--danger btn--sm remove-pair" aria-label="Remove link">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:16px;height:16px;"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" class="btn btn--outline btn--sm add-pair" data-target="navRows">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width:16px;height:16px;"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                            Add Link
                        </button>
                    </div>

                    <div class="form-grid" style="margin-top: 20px;">
                        <div class="field field--full">
                            <label class="check">
                                <input type="checkbox" name="show_nav_cta" value="1" {{ old('show_nav_cta', $settings->show_nav_cta) ? 'checked' : '' }}>
                                <span class="check__box">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                </span>
                                Show CTA button in navbar
                            </label>
                        </div>
                        <div class="field @error('nav_cta_label') has-error @enderror">
                            <label class="field__label" for="nav_cta_label">CTA Button Label</label>
                            <input class="input" type="text" id="nav_cta_label" name="nav_cta_label"
                                   value="{{ old('nav_cta_label', $settings->nav_cta_label) }}" placeholder="Hire Me">
                            @error('nav_cta_label') <span class="field__error">{{ $message }}</span> @enderror
                        </div>
                        <div class="field @error('nav_cta_url') has-error @enderror">
                            <label class="field__label" for="nav_cta_url">CTA Button URL</label>
                            <input class="input" type="text" id="nav_cta_url" name="nav_cta_url"
                                   value="{{ old('nav_cta_url', $settings->nav_cta_url) }}" placeholder="#contact">
                            @error('nav_cta_url') <span class="field__error">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="field field--full @error('notification_email') has-error @enderror" style="margin-top: 20px;">
                        <label class="field__label" for="notification_email">Contact Form Recipient Email</label>
                        <input class="input" type="email" id="notification_email" name="notification_email"
                               value="{{ old('notification_email', $settings->notification_email) }}" placeholder="you@example.com">
                        <span class="field__hint">Where contact form messages are sent. Leave blank to use the profile email (<strong>{{ \App\Models\Profile::first()?->email ?? 'not set' }}</strong>).</span>
                        @error('notification_email') <span class="field__error">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            {{-- ===================== STATS ===================== --}}
            <div class="card section">
                <div class="card__head">
                    <div>
                        <h3>About Section Stats</h3>
                        <p>The number counters shown in the "About Me" section. Drag to reorder.</p>
                    </div>
                </div>
                <div class="card__body">
                    <div class="field field--full">
                        <span class="field__hint">
                            <strong>Value</strong> can be a number (e.g. <code>50</code>) or a dynamic counter:
                            <code>{experience_years}</code>, <code>{experience_count}</code>, <code>{project_count}</code>, <code>{skill_count}</code>.
                            Blank rows are ignored.
                        </span>

                        <div id="statRows" class="pair-rows sortable-list">
                            <?php
                                $statItems = old('stats_items') ?: (is_array($settings->stats_items) ? $settings->stats_items : []);
                                if (empty($statItems)) {
                                    $statItems = [
                                        ['value' => '{experience_years}', 'label' => 'Years Experience'],
                                        ['value' => '{project_count}', 'label' => 'Projects Completed'],
                                        ['value' => '{skill_count}', 'label' => 'Technologies'],
                                    ];
                                }
                            ?>
                            @foreach ($statItems as $stat)
                                <div class="pair-row">
                                    <span class="drag-handle" aria-label="Drag to reorder" title="Drag to reorder"><svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="6" r="1"/><circle cx="9" cy="12" r="1"/><circle cx="9" cy="18" r="1"/><circle cx="15" cy="6" r="1"/><circle cx="15" cy="12" r="1"/><circle cx="15" cy="18" r="1"/></svg></span>
                                    <input class="input stat-value" type="text" value="{{ $stat['value'] ?? '' }}" placeholder="50 or {project_count}" style="flex:0 0 200px;">
                                    <input class="input stat-label" type="text" value="{{ $stat['label'] ?? '' }}" placeholder="Label (e.g. Happy Clients)">
                                    <button type="button" class="btn btn--danger btn--sm remove-pair" aria-label="Remove stat">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:16px;height:16px;"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" class="btn btn--outline btn--sm add-stat" data-target="statRows">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width:16px;height:16px;"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                            Add Stat
                        </button>
                    </div>
                </div>
            </div>

            {{-- ===================== SECTION HEADINGS ===================== --}}
            <div class="card section">
                <div class="card__head">
                    <div>
                        <h3>Section Headings &amp; Text</h3>
                        <p>Edit the titles, subtitles, and empty-state messages for each public page section.</p>
                    </div>
                </div>
                <div class="card__body">
                    @foreach (['about' => 'About Section', 'skills' => 'Skills Section', 'experience' => 'Experience Section', 'education' => 'Education Section', 'projects' => 'Projects Section', 'training' => 'Training Section', 'contact' => 'Contact Section'] as $key => $label)
                    <div class="form-grid" style="margin-bottom:8px;padding-bottom:8px;border-bottom:1px solid #f0f0f0;">
                        <div class="field field--full">
                            <label class="field__label">{{ $label }}</label>
                        </div>
                        <div class="field">
                            <input class="input" type="text" name="section_content[{{ $key }}][subtitle]"
                                   value="{{ old('section_content.' . $key . '.subtitle', $settings->sections()[$key]['subtitle'] ?? '') }}" placeholder="Subtitle">
                            <span class="field__hint">Subtitle (small text above title)</span>
                        </div>
                        <div class="field">
                            <input class="input" type="text" name="section_content[{{ $key }}][title]"
                                   value="{{ old('section_content.' . $key . '.title', $settings->sections()[$key]['title'] ?? '') }}" placeholder="Title">
                            <span class="field__hint">Main heading</span>
                        </div>
                        @if($key !== 'about' && $key !== 'contact')
                        <div class="field field--full">
                            <input class="input" type="text" name="section_content[{{ $key }}][empty]"
                                   value="{{ old('section_content.' . $key . '.empty', $settings->sections()[$key]['empty'] ?? '') }}" placeholder="Empty state message">
                            <span class="field__hint">Shown when no records exist</span>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- ===================== HERO ===================== --}}
            <?php $hero = old('hero_content') ?: $settings->hero(); ?>
            <div class="card section">
                <div class="card__head">
                    <div>
                        <h3>Hero Section</h3>
                        <p>Greeting text, button labels, and rotating typed titles.</p>
                    </div>
                </div>
                <div class="card__body">
                    <div class="form-grid">
                        <div class="field @error('hero_content.greeting') has-error @enderror">
                            <label class="field__label" for="hero_greeting">Greeting Text</label>
                            <input class="input" type="text" id="hero_greeting" name="hero_content[greeting]"
                                   value="{{ $hero['greeting'] ?? '' }}" placeholder="Hello, I'm">
                            @error('hero_content.greeting') <span class="field__error">{{ $message }}</span> @enderror
                        </div>
                        <div class="field @error('hero_content.download_cv') has-error @enderror">
                            <label class="field__label" for="hero_download_cv">Download CV Button Label</label>
                            <input class="input" type="text" id="hero_download_cv" name="hero_content[download_cv]"
                                   value="{{ $hero['download_cv'] ?? '' }}" placeholder="Download CV">
                            @error('hero_content.download_cv') <span class="field__error">{{ $message }}</span> @enderror
                        </div>
                        <div class="field @error('hero_content.contact_me') has-error @enderror">
                            <label class="field__label" for="hero_contact_me">Contact Me Button Label</label>
                            <input class="input" type="text" id="hero_contact_me" name="hero_content[contact_me]"
                                   value="{{ $hero['contact_me'] ?? '' }}" placeholder="Contact Me">
                            @error('hero_content.contact_me') <span class="field__error">{{ $message }}</span> @enderror
                        </div>
                        <div class="field field--full @error('hero_content.typed_titles') has-error @enderror">
                            <label class="field__label" for="hero_typed_titles">Typed Rotation Titles</label>
                            <input class="input" type="text" id="hero_typed_titles" name="hero_content[typed_titles]"
                                   value="{{ $hero['typed_titles'] ?? '' }}" placeholder="Full Stack Developer, Software Engineer, Problem Solver">
                            <span class="field__hint">Comma-separated. These rotate in the hero title (in addition to the profile title/tagline).</span>
                            @error('hero_content.typed_titles') <span class="field__error">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===================== CONTACT ===================== --}}
            <?php $contact = old('contact_content') ?: $settings->contact(); ?>
            <div class="card section">
                <div class="card__head">
                    <div>
                        <h3>Contact Section Text</h3>
                        <p>Heading, description, and form field labels.</p>
                    </div>
                </div>
                <div class="card__body">
                    <div class="form-grid">
                        <div class="field field--full @error('contact_content.heading') has-error @enderror">
                            <label class="field__label" for="contact_heading">Contact Heading</label>
                            <input class="input" type="text" id="contact_heading" name="contact_content[heading]"
                                   value="{{ $contact['heading'] ?? '' }}" placeholder="Let's Talk About Your Project">
                            @error('contact_content.heading') <span class="field__error">{{ $message }}</span> @enderror
                        </div>
                        <div class="field field--full @error('contact_content.description') has-error @enderror">
                            <label class="field__label" for="contact_description">Contact Description</label>
                            <textarea class="textarea" id="contact_description" name="contact_content[description]" rows="2"
                                      placeholder="Feel free to reach out...">{{ $contact['description'] ?? '' }}</textarea>
                            @error('contact_content.description') <span class="field__error">{{ $message }}</span> @enderror
                        </div>
                        <div class="field @error('contact_content.send_message') has-error @enderror">
                            <label class="field__label" for="contact_send">Submit Button Label</label>
                            <input class="input" type="text" id="contact_send" name="contact_content[send_message]"
                                   value="{{ $contact['send_message'] ?? '' }}" placeholder="Send Message">
                            @error('contact_content.send_message') <span class="field__error">{{ $message }}</span> @enderror
                        </div>
                        <div class="field @error('contact_content.name') has-error @enderror">
                            <label class="field__label">Name Field Label</label>
                            <input class="input" type="text" name="contact_content[name]"
                                   value="{{ $contact['name'] ?? '' }}" placeholder="Your Name">
                            @error('contact_content.name') <span class="field__error">{{ $message }}</span> @enderror
                        </div>
                        <div class="field @error('contact_content.email') has-error @enderror">
                            <label class="field__label">Email Field Label</label>
                            <input class="input" type="text" name="contact_content[email]"
                                   value="{{ $contact['email'] ?? '' }}" placeholder="Your Email">
                            @error('contact_content.email') <span class="field__error">{{ $message }}</span> @enderror
                        </div>
                        <div class="field @error('contact_content.subject') has-error @enderror">
                            <label class="field__label">Subject Field Label</label>
                            <input class="input" type="text" name="contact_content[subject]"
                                   value="{{ $contact['subject'] ?? '' }}" placeholder="Subject">
                            @error('contact_content.subject') <span class="field__error">{{ $message }}</span> @enderror
                        </div>
                        <div class="field @error('contact_content.phone') has-error @enderror">
                            <label class="field__label">Phone Field Label</label>
                            <input class="input" type="text" name="contact_content[phone]"
                                   value="{{ $contact['phone'] ?? '' }}" placeholder="Phone (optional)">
                            @error('contact_content.phone') <span class="field__error">{{ $message }}</span> @enderror
                        </div>
                        <div class="field @error('contact_content.message') has-error @enderror">
                            <label class="field__label">Message Field Label</label>
                            <input class="input" type="text" name="contact_content[message]"
                                   value="{{ $contact['message'] ?? '' }}" placeholder="Your Message">
                            @error('contact_content.message') <span class="field__error">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===================== LABELS ===================== --}}
            <?php $labelsData = old('labels') ?: $settings->labels(); ?>
            <div class="card section">
                <div class="card__head">
                    <div>
                        <h3>Timeline &amp; Badge Labels</h3>
                        <p>Reusable text labels used across the public site.</p>
                    </div>
                </div>
                <div class="card__body">
                    <div class="form-grid">
                        <div class="field">
                            <label class="field__label" for="label_present">"Present" Label</label>
                            <input class="input" type="text" id="label_present" name="labels[present]"
                                   value="{{ $labelsData['present'] ?? '' }}" placeholder="Present">
                            <span class="field__hint">Shown for ongoing experience/education.</span>
                        </div>
                        <div class="field">
                            <label class="field__label" for="label_current">"Current" Badge</label>
                            <input class="input" type="text" id="label_current" name="labels[current]"
                                   value="{{ $labelsData['current'] ?? '' }}" placeholder="Current">
                            <span class="field__hint">Badge on current experience.</span>
                        </div>
                        <div class="field">
                            <label class="field__label" for="label_featured">"Featured" Badge</label>
                            <input class="input" type="text" id="label_featured" name="labels[featured]"
                                   value="{{ $labelsData['featured'] ?? '' }}" placeholder="Featured">
                            <span class="field__hint">Badge on featured projects.</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===================== ADMIN BRANDING ===================== --}}
            <?php $branding = old('admin_branding') ?: $settings->branding(); ?>
            <div class="card section">
                <div class="card__head">
                    <div>
                        <h3>Admin Panel Branding</h3>
                        <p>Title, brand mark, and panel name shown in the admin interface.</p>
                    </div>
                </div>
                <div class="card__body">
                    <div class="form-grid">
                        <div class="field">
                            <label class="field__label" for="branding_title">Brand Title</label>
                            <input class="input" type="text" id="branding_title" name="admin_branding[title]"
                                   value="{{ $branding['title'] ?? '' }}" placeholder="Rasel Bepari">
                            <span class="field__hint">Shown in browser tab title &amp; sidebar.</span>
                        </div>
                        <div class="field">
                            <label class="field__label" for="branding_mark">Brand Mark</label>
                            <input class="input" type="text" id="branding_mark" name="admin_branding[mark]"
                                   value="{{ $branding['mark'] ?? '' }}" placeholder="RB" maxlength="10">
                            <span class="field__hint">Short initials shown in the logo circle.</span>
                        </div>
                        <div class="field">
                            <label class="field__label" for="branding_panel">Panel Name</label>
                            <input class="input" type="text" id="branding_panel" name="admin_branding[panel_name]"
                                   value="{{ $branding['panel_name'] ?? '' }}" placeholder="Admin Panel">
                            <span class="field__hint">Subtitle under the brand mark.</span>
                        </div>
                    </div>

                    <div class="field field--full @error('favicon') has-error @enderror" style="margin-top:20px;">
                        <label class="field__label">Favicon (Browser Tab Icon)</label>
                        <div style="display:flex;align-items:flex-start;gap:20px;flex-wrap:wrap;">
                            @php $faviconUrl = $settings->faviconUrl(); @endphp
                            @if($faviconUrl)
                            <div style="flex-shrink:0;display:flex;flex-direction:column;align-items:center;gap:8px;">
                                <div style="width:64px;height:64px;border:1px solid #e2e8f0;border-radius:10px;background:#f8fafc;display:flex;align-items:center;justify-content:center;">
                                    <img src="{{ $faviconUrl }}" alt="Favicon" style="max-width:40px;max-height:40px;">
                                </div>
                                <span style="font-size:11px;color:#94a3b8;">Current</span>
                            </div>
                            @endif
                            <div style="flex:1;min-width:200px;">
                                <div class="file-input-wrap">
                                    <label class="file-drop" for="favicon" id="faviconLabel">
                                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                                        <strong>Click to upload a favicon</strong>
                                        @if($settings->favicon)
                                            Current: <span class="text-muted">{{ \Illuminate\Support\Str::afterLast($settings->favicon, '/') }}</span>
                                        @else
                                            No favicon set · ICO, PNG, SVG, GIF up to 512KB. Falls back to logo.
                                        @endif
                                    </label>
                                    <input class="file-input" type="file" id="favicon" name="favicon" accept=".ico,.png,.jpg,.jpeg,.gif,.webp,.svg,image/*">
                                </div>
                                @if($settings->favicon)
                                <label class="check" style="margin-top:10px;">
                                    <input type="checkbox" name="remove_favicon" value="1">
                                    <span class="check__box"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg></span>
                                    Remove favicon
                                </label>
                                @endif
                            </div>
                        </div>
                        @error('favicon') <span class="field__error" style="margin-top:10px;display:block;">{{ $message }}</span> @enderror
                    </div>

                    <div class="field @error('default_theme') has-error @enderror" style="margin-top:20px;">
                        <label class="field__label" for="default_theme">Default Theme</label>
                        <select class="select" id="default_theme" name="default_theme">
                            <option value="system" {{ old('default_theme', $settings->default_theme ?? 'system') === 'system' ? 'selected' : '' }}>System (Auto-detect)</option>
                            <option value="light" {{ old('default_theme', $settings->default_theme ?? 'system') === 'light' ? 'selected' : '' }}>Light Mode</option>
                            <option value="dark" {{ old('default_theme', $settings->default_theme ?? 'system') === 'dark' ? 'selected' : '' }}>Dark Mode</option>
                        </select>
                        <span class="field__hint">The theme first-time visitors see. Each visitor can override it with the toggle button.</span>
                        @error('default_theme') <span class="field__error">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            {{-- ===================== FOOTER ===================== --}}
            <div class="card section">
                <div class="card__head">
                    <div>
                        <h3>Footer</h3>
                        <p>Manage the footer text, links and social icons.</p>
                    </div>
                </div>
                <div class="card__body">
                    <div class="field field--full @error('footer_about') has-error @enderror">
                        <label class="field__label" for="footer_about">Footer About Text</label>
                        <textarea class="textarea" id="footer_about" name="footer_about" rows="3"
                                  placeholder="A short description shown in the footer.">{{ old('footer_about', $settings->footer_about) }}</textarea>
                        @error('footer_about') <span class="field__error">{{ $message }}</span> @enderror
                    </div>

                    <div class="field field--full @error('footer_copyright') has-error @enderror">
                        <label class="field__label" for="footer_copyright">Copyright Text</label>
                        <input class="input" type="text" id="footer_copyright" name="footer_copyright"
                               value="{{ old('footer_copyright', $settings->footer_copyright) }}"
                               placeholder="© {year} {name}. All rights reserved.">
                        <span class="field__hint">Use <code>{year}</code> for the current year and <code>{name}</code> for the profile name.</span>
                        @error('footer_copyright') <span class="field__error">{{ $message }}</span> @enderror
                    </div>

                    <div class="field field--full">
                        <label class="field__label">Footer Links</label>
                        <div id="footerNavRows" class="pair-rows sortable-list">
                            <?php
                                $footerNavItems = old('footer_nav_items') ?: $settings->footerNavItems();
                                if (empty($footerNavItems)) {
                                    $footerNavItems = [
                                        ['label' => 'Home', 'url' => '#home'],
                                        ['label' => 'About', 'url' => '#about'],
                                        ['label' => 'Projects', 'url' => '#projects'],
                                        ['label' => 'Contact', 'url' => '#contact'],
                                    ];
                                }
                            ?>
                            @foreach ($footerNavItems as $item)
                                <div class="pair-row">
                                    <span class="drag-handle" aria-label="Drag to reorder" title="Drag to reorder"><svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="6" r="1"/><circle cx="9" cy="12" r="1"/><circle cx="9" cy="18" r="1"/><circle cx="15" cy="6" r="1"/><circle cx="15" cy="12" r="1"/><circle cx="15" cy="18" r="1"/></svg></span>
                                    <input class="input pair-label" type="text" value="{{ is_array($item) ? ($item['label'] ?? '') : $item }}" placeholder="Label">
                                    <input class="input pair-url" type="text" value="{{ is_array($item) ? ($item['url'] ?? '') : '' }}" placeholder="URL / #anchor">
                                    <button type="button" class="btn btn--danger btn--sm remove-pair" aria-label="Remove link">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:16px;height:16px;"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" class="btn btn--outline btn--sm add-pair" data-target="footerNavRows">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width:16px;height:16px;"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                            Add Link
                        </button>
                    </div>

                    <div class="form-grid" style="margin-top: 20px;">
                        <div class="field @error('footer_social_facebook') has-error @enderror">
                            <label class="field__label" for="footer_social_facebook">Facebook URL</label>
                            <input class="input" type="text" id="footer_social_facebook" name="footer_social_facebook"
                                   value="{{ old('footer_social_facebook', $settings->footer_social_facebook) }}" placeholder="https://facebook.com/...">
                            @error('footer_social_facebook') <span class="field__error">{{ $message }}</span> @enderror
                        </div>
                        <div class="field @error('footer_social_linkedin') has-error @enderror">
                            <label class="field__label" for="footer_social_linkedin">LinkedIn URL</label>
                            <input class="input" type="text" id="footer_social_linkedin" name="footer_social_linkedin"
                                   value="{{ old('footer_social_linkedin', $settings->footer_social_linkedin) }}" placeholder="https://linkedin.com/in/...">
                            @error('footer_social_linkedin') <span class="field__error">{{ $message }}</span> @enderror
                        </div>
                        <div class="field @error('footer_social_github') has-error @enderror">
                            <label class="field__label" for="footer_social_github">GitHub URL</label>
                            <input class="input" type="text" id="footer_social_github" name="footer_social_github"
                                   value="{{ old('footer_social_github', $settings->footer_social_github) }}" placeholder="https://github.com/...">
                            @error('footer_social_github') <span class="field__error">{{ $message }}</span> @enderror
                        </div>
                        <div class="field @error('footer_social_twitter') has-error @enderror">
                            <label class="field__label" for="footer_social_twitter">Twitter / X URL</label>
                            <input class="input" type="text" id="footer_social_twitter" name="footer_social_twitter"
                                   value="{{ old('footer_social_twitter', $settings->footer_social_twitter) }}" placeholder="https://twitter.com/...">
                            @error('footer_social_twitter') <span class="field__error">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===================== SEO ===================== --}}
            <div class="card section">
                <div class="card__head"><h3>SEO / Meta Tags</h3></div>
                <div class="card__body">
                    <div class="form-grid">
                        <div class="field field--full @error('meta_title') has-error @enderror">
                            <label class="field__label" for="meta_title">Meta Title</label>
                            <input class="input" type="text" id="meta_title" name="meta_title"
                                   value="{{ old('meta_title', $settings->meta_title) }}" placeholder="Rasel Bepari | Full Stack Developer">
                            @error('meta_title') <span class="field__error">{{ $message }}</span> @enderror
                        </div>
                        <div class="field field--full @error('meta_description') has-error @enderror">
                            <label class="field__label" for="meta_description">Meta Description</label>
                            <textarea class="textarea" id="meta_description" name="meta_description" rows="3"
                                      placeholder="A short description used by search engines.">{{ old('meta_description', $settings->meta_description) }}</textarea>
                            @error('meta_description') <span class="field__error">{{ $message }}</span> @enderror
                        </div>
                        <div class="field field--full @error('meta_keywords') has-error @enderror">
                            <label class="field__label" for="meta_keywords">Meta Keywords</label>
                            <input class="input" type="text" id="meta_keywords" name="meta_keywords"
                                   value="{{ old('meta_keywords', $settings->meta_keywords) }}" placeholder="laravel, full stack, developer">
                            <span class="field__hint">Comma separated.</span>
                            @error('meta_keywords') <span class="field__error">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.dashboard') }}" class="btn btn--outline">Cancel</a>
                <button type="submit" class="btn btn--primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                    Save Settings
                </button>
            </div>
        </form>

    </div>

@push('scripts')
<script>
(function () {
    var removeSvg = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:16px;height:16px;"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>';

    var handleSvg = '<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="6" r="1"/><circle cx="9" cy="12" r="1"/><circle cx="9" cy="18" r="1"/><circle cx="15" cy="6" r="1"/><circle cx="15" cy="12" r="1"/><circle cx="15" cy="18" r="1"/></svg>';

    function makeRow() {
        var row = document.createElement('div');
        row.className = 'pair-row';

        var handle = document.createElement('span');
        handle.className = 'drag-handle';
        handle.setAttribute('aria-label', 'Drag to reorder');
        handle.setAttribute('title', 'Drag to reorder');
        handle.innerHTML = handleSvg;

        var labelInput = document.createElement('input');
        labelInput.className = 'input pair-label';
        labelInput.type = 'text';
        labelInput.setAttribute('placeholder', 'Label');

        var urlInput = document.createElement('input');
        urlInput.className = 'input pair-url';
        urlInput.type = 'text';
        urlInput.setAttribute('placeholder', 'URL / #anchor');

        var removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className = 'btn btn--danger btn--sm remove-pair';
        removeBtn.setAttribute('aria-label', 'Remove link');
        removeBtn.innerHTML = removeSvg;

        row.appendChild(handle);
        row.appendChild(labelInput);
        row.appendChild(urlInput);
        row.appendChild(removeBtn);
        return row;
    }

    document.querySelectorAll('.add-pair').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var target = document.getElementById(btn.getAttribute('data-target'));
            if (target) target.appendChild(makeRow());
        });
    });

    /* ---- Stat rows (value + label, different placeholders) ---- */

    function makeStatRow() {
        var row = document.createElement('div');
        row.className = 'pair-row';

        var handle = document.createElement('span');
        handle.className = 'drag-handle';
        handle.setAttribute('aria-label', 'Drag to reorder');
        handle.setAttribute('title', 'Drag to reorder');
        handle.innerHTML = handleSvg;

        var valueInput = document.createElement('input');
        valueInput.className = 'input stat-value';
        valueInput.type = 'text';
        valueInput.setAttribute('placeholder', '50 or {project_count}');
        valueInput.style.flex = '0 0 200px';

        var labelInput = document.createElement('input');
        labelInput.className = 'input stat-label';
        labelInput.type = 'text';
        labelInput.setAttribute('placeholder', 'Label (e.g. Happy Clients)');

        var removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className = 'btn btn--danger btn--sm remove-pair';
        removeBtn.setAttribute('aria-label', 'Remove stat');
        removeBtn.innerHTML = removeSvg;

        row.appendChild(handle);
        row.appendChild(valueInput);
        row.appendChild(labelInput);
        row.appendChild(removeBtn);
        return row;
    }

    document.querySelectorAll('.add-stat').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var target = document.getElementById(btn.getAttribute('data-target'));
            if (target) target.appendChild(makeStatRow());
        });
    });

    document.addEventListener('click', function (e) {
        var btn = e.target.closest('.remove-pair');
        if (btn) btn.closest('.pair-row').remove();
    });

    /* ===================== Drag & Drop Reordering ===================== */

    function initSortable(list) {
        var dragSrc = null;

        function getRowAfterY(container, y) {
            var rows = container.querySelectorAll('.pair-row:not(.dragging)');
            var closest = { offset: Number.NEGATIVE_INFINITY, element: null };
            rows.forEach(function (row) {
                var box = row.getBoundingClientRect();
                var offset = y - box.top - box.height / 2;
                if (offset < 0 && offset > closest.offset) {
                    closest.offset = offset;
                    closest.element = row;
                }
            });
            return closest.element;
        }

        list.addEventListener('dragover', function (e) {
            e.preventDefault();
            var dragging = list.querySelector('.dragging');
            if (!dragging) return;
            var afterEl = getRowAfterY(list, e.clientY);
            if (afterEl == null) {
                list.appendChild(dragging);
            } else {
                list.insertBefore(dragging, afterEl);
            }
        });

        list.addEventListener('dragenter', function (e) { e.preventDefault(); });

        // Delegate dragstart / dragend to handle dynamically-added rows
        list.addEventListener('dragstart', function (e) {
            var row = e.target.closest('.pair-row');
            if (!row) return;
            dragSrc = row;
            row.classList.add('dragging');
        });

        list.addEventListener('dragend', function (e) {
            var row = e.target.closest('.pair-row');
            if (!row) return;
            row.classList.remove('dragging');
            list.querySelectorAll('.pair-row').forEach(function (r) {
                r.classList.remove('drag-over');
            });
            dragSrc = null;
        });
    }

    // Only allow drag to start from the handle, not from inputs.
    document.addEventListener('mousedown', function (e) {
        var handle = e.target.closest('.drag-handle');
        if (handle) {
            var row = handle.closest('.pair-row');
            if (row) row.setAttribute('draggable', 'true');
        }
    });

    document.addEventListener('mouseup', function (e) {
        if (!e.target.closest('.drag-handle')) {
            document.querySelectorAll('.pair-row[draggable="true"]').forEach(function (r) {
                r.removeAttribute('draggable');
            });
        }
    });

    document.addEventListener('dragend', function () {
        document.querySelectorAll('.pair-row[draggable="true"]').forEach(function (r) {
            r.removeAttribute('draggable');
        });
    });

    document.querySelectorAll('.sortable-list').forEach(initSortable);

    /* ===================== Submit: assign ordered indexed names ===================== */

    var form = document.getElementById('settingsForm');
    if (form) {
        form.addEventListener('submit', function () {
            document.querySelectorAll('.pair-rows').forEach(function (container) {
                var index = 0;

                if (container.id === 'statRows') {
                    // Stats: value + label pairs
                    container.querySelectorAll('.pair-row').forEach(function (row) {
                        var valueInput = row.querySelector('.stat-value');
                        var labelInput = row.querySelector('.stat-label');
                        var value = (valueInput && valueInput.value || '').trim();
                        var label = (labelInput && labelInput.value || '').trim();

                        if (valueInput) valueInput.removeAttribute('name');
                        if (labelInput) labelInput.removeAttribute('name');

                        if (value && label) {
                            valueInput.setAttribute('name', 'stats_items[' + index + '][value]');
                            labelInput.setAttribute('name', 'stats_items[' + index + '][label]');
                            index++;
                        }
                    });
                } else {
                    // Nav / footer: label + url pairs
                    var fieldName = container.id === 'navRows' ? 'nav_items' : 'footer_nav_items';
                    container.querySelectorAll('.pair-row').forEach(function (row) {
                        var labelInput = row.querySelector('.pair-label');
                        var urlInput = row.querySelector('.pair-url');
                        var label = (labelInput && labelInput.value || '').trim();
                        var url = (urlInput && urlInput.value || '').trim();

                        if (labelInput) labelInput.removeAttribute('name');
                        if (urlInput) urlInput.removeAttribute('name');

                        if (label && url) {
                            labelInput.setAttribute('name', fieldName + '[' + index + '][label]');
                            urlInput.setAttribute('name', fieldName + '[' + index + '][url]');
                            index++;
                        }
                    });
                }
            });
        });
    }
})();
</script>
@endpush

@endsection
