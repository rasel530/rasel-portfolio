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
    <title>Forgot Password · {{ ($branding['title'] ?? 'Portfolio') }}</title>

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
                <h1 class="login-card__title">Forgot Password</h1>
                <p class="login-card__sub">Enter your email and we'll send you a password reset link.</p>
            </div>

            @if (session('status'))
                <div class="alert alert--success" role="alert">
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert--error" role="alert">
                    <span>Please fix the highlighted fields below.</span>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="login-field field @error('email') has-error @enderror">
                    <label class="field__label" for="email">Email Address <span class="req">*</span></label>
                    <input class="input" type="email" id="email" name="email"
                           value="{{ old('email') }}" placeholder="you@example.com"
                           autocomplete="email" autofocus required>
                    @error('email') <span class="field__error">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="btn btn--primary btn--lg btn--block" style="margin-top: 22px;">
                    Send Reset Link
                </button>
            </form>

            <div class="login-card__footer">
                <a href="{{ route('admin.login') }}">&larr; Back to login</a>
            </div>
        </div>
    </div>

</body>
</html>
