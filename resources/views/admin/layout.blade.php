@php
    $unreadCount = \App\Models\Message::where('is_read', false)->count();
    $adminUser = auth()->user();
    $initials = $adminUser ? strtoupper(substr($adminUser->name, 0, 1)) : 'A';
    $branding = ($siteSetting ?? null)?->branding() ?? [];
    $brandTitle = $branding['title'] ?? 'Portfolio';
    $brandMark = $branding['mark'] ?? 'RB';
    $brandPanel = $branding['panel_name'] ?? 'Admin Panel';
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if($siteSetting?->faviconUrl())
    <link rel="icon" type="image/x-icon" href="{{ $siteSetting->faviconUrl() }}">
    <link rel="shortcut icon" href="{{ $siteSetting->faviconUrl() }}">
    @endif
    <meta name="color-scheme" content="light dark">
    <script>
        (function () {
            var stored = localStorage.getItem('theme');
            var resolved = stored || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
            document.documentElement.setAttribute('data-theme', resolved);
        })();
    </script>
    <title>@yield('title', 'Dashboard') · {{ $brandTitle }} {{ $brandPanel }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>

    <div class="app-shell">

        <!-- ============================ Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar__brand">
                @php
                    $adminProfile = \App\Models\Profile::first();
                    $adminLogoLight = $adminProfile?->logoUrlForLight();
                    $adminLogoDark = $adminProfile?->logoUrlForDark();
                    $adminSingleLogo = $adminProfile?->hasSingleLogo();
                @endphp
                @if($adminLogoLight)
                    @if($adminSingleLogo)
                        <img src="{{ $adminLogoLight }}" alt="Logo" class="sidebar__brand-logo">
                    @else
                        <img src="{{ $adminLogoLight }}" alt="Logo" class="sidebar__brand-logo sidebar__brand-logo--light">
                        <img src="{{ $adminLogoDark }}" alt="Logo" class="sidebar__brand-logo sidebar__brand-logo--dark">
                    @endif
                @else
                    <div class="sidebar__brand-mark">{{ $brandMark }}</div>
                @endif
                <div>
                    <div class="sidebar__brand-name">{{ $adminProfile?->name ?? $brandTitle }}</div>
                    <div class="sidebar__brand-sub">{{ $brandPanel }}</div>
                </div>
            </div>

            <nav class="sidebar__nav">
                <div class="sidebar__label">Manage</div>

                <a href="{{ route('admin.dashboard') }}"
                   class="sidebar__link @if(request()->routeIs('admin.dashboard')) is-active @endif">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                    Dashboard
                </a>

                <a href="{{ route('admin.profiles.edit', optional(\App\Models\Profile::first())->id ?? 1) }}"
                   class="sidebar__link @if(request()->routeIs('admin.profiles.*')) is-active @endif">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    Profile
                </a>

                <a href="{{ route('admin.skills.index') }}"
                   class="sidebar__link @if(request()->routeIs('admin.skills.*')) is-active @endif">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                    Skills
                </a>

                <a href="{{ route('admin.experiences.index') }}"
                   class="sidebar__link @if(request()->routeIs('admin.experiences.*')) is-active @endif">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
                    Experience
                </a>

                <a href="{{ route('admin.educations.index') }}"
                   class="sidebar__link @if(request()->routeIs('admin.educations.*')) is-active @endif">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>
                    Education
                </a>

                <a href="{{ route('admin.projects.index') }}"
                   class="sidebar__link @if(request()->routeIs('admin.projects.*')) is-active @endif">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>
                    Projects
                </a>

                <a href="{{ route('admin.trainings.index') }}"
                   class="sidebar__link @if(request()->routeIs('admin.trainings.*')) is-active @endif">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                    Training
                </a>

                <a href="{{ route('admin.messages.index') }}"
                   class="sidebar__link @if(request()->routeIs('admin.messages.*')) is-active @endif">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                    Messages
                    @if ($unreadCount > 0)
                        <span class="sidebar__badge">{{ $unreadCount }}</span>
                    @endif
                </a>

                @can('manage-settings')
                <a href="{{ route('admin.settings.edit') }}"
                   class="sidebar__link @if(request()->routeIs('admin.settings.*')) is-active @endif">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                    Settings
                </a>
                @endcan

                @can('manage-users')
                <a href="{{ route('admin.users.index') }}"
                   class="sidebar__link @if(request()->routeIs('admin.users.*')) is-active @endif">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    Users
                </a>
                @endcan
            </nav>

            <div class="sidebar__foot">
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="sidebar__link" style="width:100%;border:none;background:none;cursor:pointer;font:inherit;text-align:left;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

        <!-- ============================ Main -->
        <div class="main">

            <header class="topbar">
                <button type="button" class="topbar__toggle" id="sidebarToggle" aria-label="Toggle menu">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
                </button>

                <div class="topbar__title">
                    <h1>{{ $title ?? 'Dashboard' }}</h1>
                    <p>{{ $subtitle ?? 'Welcome back, manage your portfolio content.' }}</p>
                </div>

                <div class="topbar__spacer"></div>

                <button class="topbar__theme" id="adminThemeToggle" aria-label="Toggle dark mode" title="Toggle theme" style="width:38px;height:38px;border-radius:50%;border:1px solid var(--border,#e2e8f0);background:transparent;cursor:pointer;display:flex;align-items:center;justify-content:center;color:inherit;margin-right:12px;">
                    <svg class="icon-sun" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none;"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
                    <svg class="icon-moon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
                </button>

                <div class="topbar__user">
                    <div class="topbar__user-meta">
                        <div class="topbar__user-name">{{ $adminUser->name ?? 'Administrator' }}</div>
                        <div class="topbar__user-role">{{ ucfirst($adminUser->role ?? 'admin') }}</div>
                    </div>
                    <div class="topbar__avatar">{{ $initials }}</div>
                </div>
            </header>

            <main class="content">
                @if (session('success'))
                    <div class="alert alert--success" role="status">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert--error" role="alert">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script>
        (function () {
            var sidebar = document.getElementById('sidebar');
            var backdrop = document.getElementById('sidebarBackdrop');
            var toggle = document.getElementById('sidebarToggle');

            function open() { sidebar.classList.add('is-open'); backdrop.classList.add('is-open'); }
            function close() { sidebar.classList.remove('is-open'); backdrop.classList.remove('is-open'); }

            if (toggle) toggle.addEventListener('click', function () {
                sidebar.classList.contains('is-open') ? close() : open();
            });
            if (backdrop) backdrop.addEventListener('click', close);
        })();

        // Theme toggle
        (function () {
            function updateIcons() {
                var isDark = document.documentElement.getAttribute('data-theme') === 'dark';
                var sun = document.querySelector('.icon-sun');
                var moon = document.querySelector('.icon-moon');
                if (sun) sun.style.display = isDark ? 'block' : 'none';
                if (moon) moon.style.display = isDark ? 'none' : 'block';
            }
            updateIcons();

            var btn = document.getElementById('adminThemeToggle');
            if (btn) {
                btn.addEventListener('click', function () {
                    var html = document.documentElement;
                    var current = html.getAttribute('data-theme') || 'light';
                    var next = current === 'dark' ? 'light' : 'dark';
                    html.setAttribute('data-theme', next);
                    localStorage.setItem('theme', next);
                    updateIcons();
                });
            }
        })();
    </script>

    @stack('scripts')
</body>
</html>
