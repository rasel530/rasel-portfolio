@extends('layout')

@section('content')

@php
    $settings = $siteSetting ?? null;

    // ---- Dynamic content groups (all admin-customizable) ----
    $sections = $settings ? $settings->sections() : [];
    $hero = $settings ? $settings->hero() : [];
    $contactText = $settings ? $settings->contact() : [];
    $labels = $settings ? $settings->labels() : [];

    // ---- Profile data (fallbacks only when no profile exists) ----
    $profileName = $profile?->name ?? ($settings?->branding()['title'] ?? 'Portfolio');
    $profileTitle = $profile?->title ?? '';
    $profileTagline = $profile?->tagline ?? '';
    $profileSummary = $profile?->summary ?? '';
    $profileEmail = $profile?->email ?? '';
    $profilePhone = $profile?->phone ?? '';
    $profileAddress = $profile?->address ?? '';
    $profilePhoto = $profile?->photo ?? '';
    $logoLightUrl = $profile?->logoUrlForLight();
    $logoDarkUrl = $profile?->logoUrlForDark();
    $singleLogo = $profile?->hasSingleLogo();
    $profileResume = $profile?->resume_url ?? '';
    $socials = array_filter([
        'facebook' => $profile?->facebook ?? '',
        'linkedin' => $profile?->linkedin ?? '',
        'github'   => $profile?->github ?? '',
        'twitter'  => $profile?->twitter ?? '',
    ]);
    $skillsByCategory = $skills->groupBy('category');

    // Typed rotation titles — profile title/tagline + admin-configured extras
    $typeTitles = $settings
        ? $settings->typedTitles($profileTitle, $profileTagline)
        : array_values(array_filter([$profileTitle, $profileTagline]));

    // ---- Navigation ----
    $navItems = $settings ? $settings->navItems() : [];
    $showNavCta = $settings ? $settings->show_nav_cta : true;
    $navCtaLabel = $settings?->nav_cta_label ?: 'Hire Me';
    $navCtaUrl = $settings?->nav_cta_url ?: '#contact';

    // ---- Footer ----
    $footerNavItems = $settings ? $settings->footerNavItems() : [];
    $footerAbout = $settings && $settings->footer_about ? $settings->footer_about : strip_tags($profileSummary);
    $footerSocials = $settings ? $settings->footerSocials() : [];
    $footerCopyright = $settings ? $settings->renderedCopyright($profileName) : '© ' . date('Y') . ' ' . $profileName;

    // ---- Statistics ----
    $stats = $settings ? $settings->parsedStats() : [];
@endphp

@section('title', $settings?->meta_title ?: ($profileName . ' | Portfolio'))

<!-- ============ NAVBAR ============ -->
<nav id="navbar" class="navbar">
    <div class="container nav-container">
        <a href="#home" class="nav-logo">
            @if($logoLightUrl)
                @if($singleLogo)
                    <img src="{{ $logoLightUrl }}" alt="{{ $profileName }}" class="nav-logo-img">
                @else
                    <img src="{{ $logoLightUrl }}" alt="{{ $profileName }}" class="nav-logo-img nav-logo-light">
                    <img src="{{ $logoDarkUrl }}" alt="{{ $profileName }}" class="nav-logo-img nav-logo-dark">
                @endif
            @else
                <span class="logo-bracket">&lt;</span>{{ $profileName }}<span class="logo-bracket">/&gt;</span>
            @endif
        </a>

        <ul class="nav-menu" id="navMenu">
            @foreach($navItems as $item)
            <li><a href="{{ $item['url'] }}" class="nav-link">{{ $item['label'] }}</a></li>
            @endforeach
            @if($showNavCta)
            <li class="nav-cta-mobile">
                <a href="{{ $navCtaUrl }}" class="btn btn-primary btn-sm">{{ $navCtaLabel }}</a>
            </li>
            @endif
        </ul>

        <div class="nav-actions">
            <button class="theme-toggle" id="themeToggle" aria-label="Toggle dark mode" title="Toggle theme">
                <svg class="theme-toggle__sun" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
                <svg class="theme-toggle__moon" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
            </button>
            @if($showNavCta)
            <a href="{{ $navCtaUrl }}" class="btn btn-primary btn-sm nav-cta">{{ $navCtaLabel }}</a>
            @endif
            <button class="hamburger" id="hamburger" aria-label="Toggle menu" aria-expanded="false">
                <span></span><span></span><span></span>
            </button>
        </div>
    </div>
</nav>

<!-- ============ HERO / HOME ============ -->
<section id="home" class="hero">
    <div class="hero-bg">
        <div class="floating-shape shape-1"></div>
        <div class="floating-shape shape-2"></div>
        <div class="floating-shape shape-3"></div>
        <div class="floating-shape shape-4"></div>
    </div>

    <div class="container hero-container">
        <div class="hero-content reveal">
            <p class="hero-greeting">{{ $hero['greeting'] ?? "Hello, I'm" }}</p>
            <h1 class="hero-name">{{ $profileName }}</h1>
            <h2 class="hero-title">
                <span class="typed-text" id="typedText" data-titles='{!! json_encode($typeTitles, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE) !!}'></span><span class="cursor">&nbsp;</span>
            </h2>
            @if(!empty($profileTagline))
            <p class="hero-tagline">{{ $profileTagline }}</p>
            @endif

            <div class="hero-buttons">
                @if(!empty($profileResume))
                @php
                    $resumeHref = filter_var($profileResume, FILTER_VALIDATE_URL)
                        ? $profileResume
                        : asset('storage/' . $profileResume);
                @endphp
                <a href="{{ $resumeHref }}" target="_blank" rel="noopener" class="btn btn-primary">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    {{ $hero['download_cv'] ?? 'Download CV' }}
                </a>
                @endif
                <a href="#contact" class="btn btn-outline">{{ $hero['contact_me'] ?? 'Contact Me' }}</a>
            </div>

            @if(!empty($socials))
            <div class="hero-socials">
                @foreach($socials as $platform => $url)
                <a href="{{ $url }}" target="_blank" rel="noopener" class="social-link" aria-label="{{ ucfirst($platform) }}">
                    @if($platform === 'facebook')
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/></svg>
                    @elseif($platform === 'linkedin')
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 0 1-2.063-2.065 2.064 2.064 0 1 1 2.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.225 0z"/></svg>
                    @elseif($platform === 'github')
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12"/></svg>
                    @elseif($platform === 'twitter')
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    @endif
                </a>
                @endforeach
            </div>
            @endif
        </div>

        <div class="hero-photo-wrap reveal">
            <div class="hero-photo-ring">
                <div class="hero-photo">
                    @if(!empty($profilePhoto))
                    <img src="{{ asset('storage/' . $profilePhoto) }}" alt="{{ $profileName }}">
                    @else
                    <div class="hero-photo-placeholder">{{ strtoupper(mb_substr($profileName, 0, 1)) }}</div>
                    @endif
                </div>
            </div>
            <div class="hero-blob"></div>
        </div>
    </div>

    <a href="#about" class="scroll-down" aria-label="Scroll down">
        <span class="mouse"><span class="wheel"></span></span>
    </a>
</section>

<!-- ============ ABOUT ============ -->
<section id="about" class="section about-section">
    <div class="container">
        <div class="section-header reveal">
            <span class="section-subtitle">{{ $sections['about']['subtitle'] ?? 'Get To Know Me' }}</span>
            <h2 class="section-title">{{ $sections['about']['title'] ?? 'About Me' }}</h2>
            <div class="section-divider"><span></span></div>
        </div>

        <div class="about-grid">
            <div class="about-text reveal">
                @if(!empty($profileSummary))
                <div class="about-text-content">{!! $profileSummary !!}</div>
                @else
                <p>{{ $sections['about']['empty'] ?? 'Passionate and detail-oriented developer dedicated to building clean, efficient, and user-friendly digital experiences.' }}</p>
                @endif
            </div>

            <div class="about-info-card reveal">
                <h3 class="about-info-title">Personal Info</h3>
                <ul class="about-info-list">
                    @if(!empty($profileEmail))
                    <li>
                        <span class="info-label">Email</span>
                        <span class="info-value"><a href="mailto:{{ $profileEmail }}">{{ $profileEmail }}</a></span>
                    </li>
                    @endif
                    @if(!empty($profilePhone))
                    <li>
                        <span class="info-label">Phone</span>
                        <span class="info-value"><a href="tel:{{ $profilePhone }}">{{ $profilePhone }}</a></span>
                    </li>
                    @endif
                    @if(!empty($profileAddress))
                    <li>
                        <span class="info-label">Address</span>
                        <span class="info-value">{{ $profileAddress }}</span>
                    </li>
                    @endif
                </ul>
            </div>
        </div>

        <div class="stats-grid">
            @foreach($stats as $stat)
            <div class="stat-card reveal">
                <h3 class="stat-number" data-target="{{ $stat['value'] }}">0</h3>
                <p class="stat-label">{{ $stat['label'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ============ SKILLS ============ -->
<section id="skills" class="section skills-section">
    <div class="container">
        <div class="section-header reveal">
            <span class="section-subtitle">{{ $sections['skills']['subtitle'] ?? 'What I Know' }}</span>
            <h2 class="section-title">{{ $sections['skills']['title'] ?? 'My Skills' }}</h2>
            <div class="section-divider"><span></span></div>
        </div>

        @if($skillsByCategory->isNotEmpty())
        <div class="skills-wrapper">
            @foreach($skillsByCategory as $category => $categorySkills)
            <div class="skill-category reveal">
                <h3 class="skill-category-title">{{ $category }}</h3>
                <div class="skill-list">
                    @foreach($categorySkills as $skill)
                    @php $level = (int) ($skill->proficiency ?? 0); @endphp
                    <div class="skill-item">
                        <div class="skill-head">
                            <span class="skill-name">{{ $skill->name }}</span>
                            <span class="skill-percent">{{ $level }}%</span>
                        </div>
                        <div class="skill-bar">
                            <div class="skill-bar-fill" data-width="{{ $level }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
        @else
        <p class="empty-state">{{ $sections['skills']['empty'] ?? 'Skills coming soon.' }}</p>
        @endif
    </div>
</section>

<!-- ============ EXPERIENCE ============ -->
<section id="experience" class="section experience-section">
    <div class="container">
        <div class="section-header reveal">
            <span class="section-subtitle">{{ $sections['experience']['subtitle'] ?? 'My Journey' }}</span>
            <h2 class="section-title">{{ $sections['experience']['title'] ?? 'Work Experience' }}</h2>
            <div class="section-divider"><span></span></div>
        </div>

        @if($totalExperience['raw_days'] > 0)
        <div class="exp-highlight reveal" style="display:flex;align-items:center;gap:24px;background:linear-gradient(135deg,var(--dark),var(--dark-2));border-radius:20px;padding:32px 40px;margin-bottom:40px;position:relative;overflow:hidden;">
            <div style="position:absolute;top:-40px;right:-40px;width:180px;height:180px;background:var(--gradient);opacity:0.08;border-radius:50%;"></div>
            <div style="position:absolute;bottom:-60px;left:30%;width:120px;height:120px;background:var(--accent);opacity:0.06;border-radius:50%;"></div>
            <div class="exp-highlight__icon" style="width:72px;height:72px;border-radius:18px;background:var(--gradient);display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 8px 24px rgba(79,70,229,0.4);">
                <svg viewBox="0 0 24 24" width="36" height="36" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
            <div style="flex:1;">
                <div class="exp-highlight__label" style="font-size:13px;text-transform:uppercase;letter-spacing:1.5px;color:var(--accent-light);font-weight:600;margin-bottom:6px;">Total Professional Experience</div>
                <div class="exp-highlight__value" style="font-family:'Poppins',sans-serif;font-size:44px;font-weight:800;line-height:1;color:#fff;">
                    {{ $totalExperience['formatted'] }}
                </div>
            </div>
            <div class="exp-highlight__stats" style="display:flex;gap:32px;">
                <div style="text-align:center;">
                    <div style="font-size:32px;font-weight:800;color:var(--accent-light);line-height:1;">{{ $experiences->count() }}</div>
                    <div style="font-size:12px;color:var(--text-light);margin-top:4px;text-transform:uppercase;letter-spacing:0.5px;">Positions</div>
                </div>
                <div style="text-align:center;">
                    <div style="font-size:32px;font-weight:800;color:var(--accent-light);line-height:1;">{{ $experiences->pluck('company')->unique()->count() }}</div>
                    <div style="font-size:12px;color:var(--text-light);margin-top:4px;text-transform:uppercase;letter-spacing:0.5px;">Companies</div>
                </div>
            </div>
        </div>
        @endif

        @if($experiences->isNotEmpty())
        <div class="timeline">
            @foreach($experiences as $exp)
            @php
                $startDate = optional($exp->start_date)->format('M Y');
                $endDate = $exp->is_current ? ($labels['present'] ?? 'Present') : (optional($exp->end_date)->format('M Y') ?? '');
            @endphp
            <div class="timeline-item reveal">
                <div class="timeline-dot"></div>
                <div class="timeline-content">
                    <span class="timeline-date">{{ $startDate }} — {{ $endDate }}</span>
                    <h3 class="timeline-title">{{ $exp->position }}</h3>
                    <span class="timeline-org">{{ $exp->company }}</span>
                    @if(!empty($exp->company_address))
                    <span class="timeline-address">{{ $exp->company_address }}</span>
                    @endif
                    @if(!empty($exp->description))
                    <div class="timeline-desc rich-content">{!! $exp->description !!}</div>
                    @endif
                    @if($exp->is_current)
                    <span class="badge badge-current">{{ $labels['current'] ?? 'Current' }}</span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @else
        <p class="empty-state">{{ $sections['experience']['empty'] ?? 'Experience details coming soon.' }}</p>
        @endif
    </div>
</section>

<!-- ============ EDUCATION ============ -->
<section id="education" class="section education-section">
    <div class="container">
        <div class="section-header reveal">
            <span class="section-subtitle">{{ $sections['education']['subtitle'] ?? 'My Background' }}</span>
            <h2 class="section-title">{{ $sections['education']['title'] ?? 'Education' }}</h2>
            <div class="section-divider"><span></span></div>
        </div>

        @if($educations->isNotEmpty())
        <div class="timeline timeline-alt">
            @foreach($educations as $edu)
            @php
                $startYear = $edu->start_year ?? '';
                $endYear = $edu->is_current ? ($labels['present'] ?? 'Present') : ($edu->end_year ?? '');
            @endphp
            <div class="timeline-item reveal">
                <div class="timeline-dot"></div>
                <div class="timeline-content">
                    <span class="timeline-date">{{ $startYear }} — {{ $endYear }}</span>
                    <h3 class="timeline-title">{{ $edu->degree }}</h3>
                    <span class="timeline-org">{{ $edu->institution }}</span>
                    @if(!empty($edu->result))
                    <span class="badge badge-result">Result: {{ $edu->result }}</span>
                    @endif
                    @if(!empty($edu->description))
                    <div class="timeline-desc rich-content">{!! $edu->description !!}</div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @else
        <p class="empty-state">{{ $sections['education']['empty'] ?? 'Education details coming soon.' }}</p>
        @endif
    </div>
</section>

<!-- ============ PROJECTS ============ -->
<section id="projects" class="section projects-section">
    <div class="container">
        <div class="section-header reveal">
            <span class="section-subtitle">{{ $sections['projects']['subtitle'] ?? 'My Work' }}</span>
            <h2 class="section-title">{{ $sections['projects']['title'] ?? 'Featured Projects' }}</h2>
            <div class="section-divider"><span></span></div>
        </div>

        @if($projects->isNotEmpty())
        <div class="projects-grid">
            @foreach($projects as $project)
            @php
                $techs = !empty($project->technologies) ? array_map('trim', explode(',', $project->technologies)) : [];
            @endphp
            <article class="project-card reveal">
                <div class="project-image">
                    @if(!empty($project->image))
                    <img src="{{ asset('storage/' . $project->image) }}" alt="{{ $project->title }}" loading="lazy">
                    @else
                    <div class="project-image-placeholder">
                        <svg viewBox="0 0 24 24" width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
                    </div>
                    @endif
                    @if($project->is_featured)
                    <span class="badge badge-featured">{{ $labels['featured'] ?? 'Featured' }}</span>
                    @endif
                    <div class="project-overlay">
                        <div class="project-links">
                            @if(!empty($project->demo_url))
                            <a href="{{ $project->demo_url }}" target="_blank" rel="noopener" class="project-link" aria-label="Live demo">
                                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                            </a>
                            @endif
                            @if(!empty($project->source_url))
                            <a href="{{ $project->source_url }}" target="_blank" rel="noopener" class="project-link" aria-label="Source code">
                                <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor"><path d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12"/></svg>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="project-body">
                    <h3 class="project-title">{{ $project->title }}</h3>
                    <div class="project-desc rich-content">{!! $project->description !!}</div>
                    @if(!empty($techs))
                    <div class="project-techs">
                        @foreach($techs as $tech)
                        <span class="tech-tag">{{ $tech }}</span>
                        @endforeach
                    </div>
                    @endif
                </div>
            </article>
            @endforeach
        </div>
        @else
        <p class="empty-state">{{ $sections['projects']['empty'] ?? 'Projects coming soon.' }}</p>
        @endif
    </div>
</section>

<!-- ============ TRAINING ============ -->
@if($trainings->isNotEmpty())
<section id="training" class="section training-section">
    <div class="container">
        <div class="section-header reveal">
            <span class="section-subtitle">{{ $sections['training']['subtitle'] ?? 'My Certifications' }}</span>
            <h2 class="section-title">{{ $sections['training']['title'] ?? 'Training & Certifications' }}</h2>
            <div class="section-divider"><span></span></div>
        </div>

        <div class="training-grid">
            @foreach($trainings as $training)
            <article class="training-card reveal">
                @if(!empty($training->image) && \Illuminate\Support\Facades\Storage::disk('public')->exists($training->image))
                <a href="{{ $training->publicUrl() }}" class="training-card__img">
                    <img src="{{ asset('storage/' . $training->image) }}" alt="{{ $training->title }}">
                </a>
                @endif
                <div class="training-card__body">
                    @if($training->yearRange())
                    <span class="training-card__date">{{ $training->yearRange() }}</span>
                    @endif
                    <h3 class="training-card__title">
                        <a href="{{ $training->publicUrl() }}">{{ $training->title }}</a>
                    </h3>
                    @if(!empty($training->organization))
                    <span class="training-card__org">{{ $training->organization }}</span>
                    @endif
                    @if(!empty($training->description))
                    <div class="training-card__desc rich-content">{!! $training->description !!}</div>
                    @endif
                    <div class="training-card__footer">
                        <a href="{{ $training->publicUrl() }}" class="btn-text-link">View Details
                            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                        </a>
                        @if(!empty($training->certificate_url))
                        <a href="{{ $training->certificate_url }}" target="_blank" rel="noopener" class="btn-text-link">
                            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                            Certificate
                        </a>
                        @endif
                    </div>
                </div>
            </article>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- ============ CONTACT ============ -->
<section id="contact" class="section contact-section">
    <div class="container">
        <div class="section-header reveal">
            <span class="section-subtitle">{{ $sections['contact']['subtitle'] ?? 'Get In Touch' }}</span>
            <h2 class="section-title">{{ $sections['contact']['title'] ?? 'Contact Me' }}</h2>
            <div class="section-divider"><span></span></div>
        </div>

        <div class="contact-grid">
            <div class="contact-info reveal">
                <h3 class="contact-info-title">{{ $contactText['heading'] ?? "Let's Talk About Your Project" }}</h3>
                <p class="contact-info-text">{{ $contactText['description'] ?? "Feel free to reach out for collaborations or just a friendly hello." }}</p>

                <ul class="contact-details">
                    @if(!empty($profileEmail))
                    <li class="contact-detail-item">
                        <span class="contact-icon">
                            <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        </span>
                        <div>
                            <span class="contact-detail-label">Email</span>
                            <a href="mailto:{{ $profileEmail }}" class="contact-detail-value">{{ $profileEmail }}</a>
                        </div>
                    </li>
                    @endif
                    @if(!empty($profilePhone))
                    <li class="contact-detail-item">
                        <span class="contact-icon">
                            <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        </span>
                        <div>
                            <span class="contact-detail-label">Phone</span>
                            <a href="tel:{{ $profilePhone }}" class="contact-detail-value">{{ $profilePhone }}</a>
                        </div>
                    </li>
                    @endif
                    @if(!empty($profileAddress))
                    <li class="contact-detail-item">
                        <span class="contact-icon">
                            <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        </span>
                        <div>
                            <span class="contact-detail-label">Address</span>
                            <span class="contact-detail-value">{{ $profileAddress }}</span>
                        </div>
                    </li>
                    @endif
                </ul>

                @if(!empty($socials))
                <div class="contact-socials">
                    @foreach($socials as $platform => $url)
                    <a href="{{ $url }}" target="_blank" rel="noopener" class="social-link" aria-label="{{ ucfirst($platform) }}">
                        @if($platform === 'facebook')
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/></svg>
                        @elseif($platform === 'linkedin')
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 0 1-2.063-2.065 2.064 2.064 0 1 1 2.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.225 0z"/></svg>
                        @elseif($platform === 'github')
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12"/></svg>
                        @elseif($platform === 'twitter')
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        @endif
                    </a>
                    @endforeach
                </div>
                @endif
            </div>

            <div class="contact-form-wrap reveal">
                @if(session('success'))
                <div class="form-alert form-alert-success" id="formSuccess">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
                @endif
                @if(session('error'))
                <div class="form-alert form-alert-error" id="formError">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
                @endif

                <div class="form-alert form-alert-success" id="ajaxSuccess" style="display:none;">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    <span>Thank you! Your message has been sent successfully.</span>
                </div>
                <div class="form-alert form-alert-error" id="ajaxError" style="display:none;">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <span id="ajaxErrorText">Something went wrong. Please try again.</span>
                </div>

                <form id="contactForm" class="contact-form" action="{{ route('contact.store') }}" method="POST" novalidate>
                    @csrf
                    {{-- Honeypot: hidden from real users, filled by bots. --}}
                    <div class="hp-field" aria-hidden="true" style="position:absolute;left:-9999px;top:-9999px;width:1px;height:1px;overflow:hidden;">
                        <label>Website (leave empty)</label>
                        <input type="text" name="website" tabindex="-1" autocomplete="off">
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <input type="text" name="name" id="name" placeholder=" " value="{{ old('name') }}" required>
                            <label for="name">{{ $contactText['name'] ?? 'Your Name' }}</label>
                            @error('name') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <input type="email" name="email" id="email" placeholder=" " value="{{ old('email') }}" required>
                            <label for="email">{{ $contactText['email'] ?? 'Your Email' }}</label>
                            @error('email') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <input type="text" name="subject" id="subject" placeholder=" " value="{{ old('subject') }}">
                            <label for="subject">{{ $contactText['subject'] ?? 'Subject' }}</label>
                            @error('subject') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <input type="tel" name="phone" id="phone" placeholder=" " value="{{ old('phone') }}">
                            <label for="phone">{{ $contactText['phone'] ?? 'Phone (optional)' }}</label>
                            @error('phone') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <textarea name="message" id="message" rows="6" placeholder=" " required>{{ old('message') }}</textarea>
                        <label for="message">{{ $contactText['message'] ?? 'Your Message' }}</label>
                        @error('message') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                    <button type="submit" class="btn btn-primary btn-block" id="submitBtn">
                        <span class="btn-text">{{ $contactText['send_message'] ?? 'Send Message' }}</span>
                        <span class="btn-loader"></span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- ============ FOOTER ============ -->
<footer class="footer">
    <div class="container footer-container">
        <div class="footer-brand">
            <a href="#home" class="footer-logo">
                @if($logoLightUrl)
                    @if($singleLogo)
                        <img src="{{ $logoLightUrl }}" alt="{{ $profileName }}" class="footer-logo-img">
                    @else
                        <img src="{{ $logoLightUrl }}" alt="{{ $profileName }}" class="footer-logo-img footer-logo-light">
                        <img src="{{ $logoDarkUrl }}" alt="{{ $profileName }}" class="footer-logo-img footer-logo-dark">
                    @endif
                @else
                    <span class="logo-bracket">&lt;</span>{{ $profileName }}<span class="logo-bracket">/&gt;</span>
                @endif
            </a>
            @if(!empty($footerAbout))
            <p class="footer-about">{{ $footerAbout }}</p>
            @endif
        </div>

        <div class="footer-links">
            @foreach($footerNavItems as $item)
            <a href="{{ $item['url'] }}">{{ $item['label'] }}</a>
            @endforeach
        </div>

        <div class="footer-socials">
            @foreach($footerSocials as $platform => $url)
            <a href="{{ $url }}" target="_blank" rel="noopener" class="social-link" aria-label="{{ ucfirst($platform) }}">
                @if($platform === 'facebook')
                <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/></svg>
                @elseif($platform === 'linkedin')
                <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 0 1-2.063-2.065 2.064 2.064 0 1 1 2.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.225 0z"/></svg>
                @elseif($platform === 'github')
                <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 21.795 24 17.295 24 12.297c0-6.627-5.373-12-12-12"/></svg>
                @elseif($platform === 'twitter')
                <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                @endif
            </a>
            @endforeach
        </div>

        <p class="footer-text">{{ $footerCopyright }}</p>
    </div>
</footer>

@endsection
