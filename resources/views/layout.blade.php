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

    {{-- Anti-FOUC: apply theme before CSS renders --}}
    <script>
        (function () {
            var stored = localStorage.getItem('theme');
            var defaultTheme = '{{ $siteSetting?->default_theme ?? 'system' }}';
            var resolved;
            if (stored === 'light' || stored === 'dark') {
                resolved = stored;
            } else if (defaultTheme === 'light' || defaultTheme === 'dark') {
                resolved = defaultTheme;
            } else {
                resolved = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            }
            document.documentElement.setAttribute('data-theme', resolved);
        })();
    </script>

    <meta name="description" content="@yield('meta_description', $siteSetting->meta_description ?? (($siteSetting?->branding()['title'] ?? 'Portfolio') . ' - Professional Portfolio'))">
    @if(!empty($siteSetting->meta_keywords))
    <meta name="keywords" content="{{ $siteSetting->meta_keywords }}">
    @endif
    <title>@yield('title', $siteSetting->meta_title ?? 'Rasel Bepari | Portfolio')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    @stack('styles')
</head>
<body>

    <div id="preloader">
        <div class="preloader-inner">
            <div class="preloader-spinner"></div>
            <p class="preloader-text">Loading</p>
        </div>
    </div>

    @yield('content')

    <button id="backToTop" aria-label="Back to top">
        <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="18 15 12 9 6 15"></polyline>
        </svg>
    </button>

    <script src="{{ asset('js/main.js') }}"></script>
    @stack('scripts')
</body>
</html>
