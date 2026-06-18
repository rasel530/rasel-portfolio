@php $branding = ($siteSetting ?? null)?->branding() ?? []; @endphp
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
    <title>{{ ($branding['panel_name'] ?? 'Admin') }} Login · {{ ($branding['title'] ?? 'Portfolio') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>

    <div class="login-screen">
        <div class="login-card">

            <div class="login-card__brand">
                <div class="login-card__mark">{{ $branding['mark'] ?? 'RB' }}</div>
                <h1 class="login-card__title">{{ $branding['panel_name'] ?? 'Admin Panel' }}</h1>
                <p class="login-card__sub">{{ ($branding['title'] ?? 'Portfolio') }} · Portfolio Management</p>
            </div>

            @if (session('error'))
                <div class="alert alert--error" role="alert">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if (session('status'))
                <div class="alert alert--info" role="alert">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="16" x2="12" y2="12"></line>
                        <line x1="12" y1="8" x2="12.01" y2="8"></line>
                    </svg>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert--error" role="alert">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <span>Please fix the highlighted fields below.</span>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.post') }}">
                @csrf

                <div class="login-field field @error('email') has-error @enderror">
                    <label class="field__label" for="email">Email Address <span class="req">*</span></label>
                    <div class="input-group">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                            <polyline points="22,6 12,13 2,6"></polyline>
                        </svg>
                        <input class="input" type="email" id="email" name="email"
                               value="{{ old('email') }}"
                               placeholder="you@example.com"
                               autocomplete="email" autofocus required>
                    </div>
                    @error('email') <span class="field__error">{{ $message }}</span> @enderror
                </div>

                <div class="login-field field @error('password') has-error @enderror">
                    <label class="field__label" for="password">Password <span class="req">*</span></label>
                    <div class="input-group">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                        <input class="input" type="password" id="password" name="password"
                               placeholder="Enter your password"
                               autocomplete="current-password" required>
                    </div>
                    @error('password') <span class="field__error">{{ $message }}</span> @enderror
                </div>

                <div class="login-meta">
                    <label class="check">
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <span class="check__box">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        </span>
                        Remember me
                    </label>
                    <a href="{{ route('password.request') }}" style="font-size: 13px;">Forgot password?</a>
                </div>

                <button type="submit" class="btn btn--primary btn--lg btn--block" style="margin-top: 22px;">
                    Sign In
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </button>
            </form>

            <div class="login-card__footer">
                <a href="{{ route('home') }}">&larr; Back to website</a>
            </div>
        </div>
    </div>

</body>
</html>
