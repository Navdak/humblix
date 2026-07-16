<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login · HUMELIX LIMITED</title>
    <link rel="icon" href="{{ asset('images/brand/humelix-favicon-32.png') }}" sizes="32x32" type="image/png">
    <link rel="apple-touch-icon" href="{{ asset('images/brand/humelix-apple-touch-icon.png') }}">
    <link rel="stylesheet" href="{{ asset('css/uch.css') }}?v=20260716d">
</head>
<body class="login-page">
    <form class="login-card" method="POST" action="{{ route('admin.login.store') }}">
        @csrf
        <div class="brand" style="margin-bottom:26px"><span class="brand-mark" aria-hidden="true"><img src="{{ asset('images/brand/humelix-logo-mark.png') }}" alt=""></span><span class="brand-text"><strong>HUMELIX</strong><small>LIMITED</small></span></div>
        <span class="eyebrow">Private Admin Access</span>
        <h1 style="font-size:34px;margin:8px 0 8px;letter-spacing:-.04em">Sign in to dashboard</h1>
        <p class="section-sub" style="margin-bottom:24px">Manage enquiries, projects, team members, reviews, media and homepage content.</p>
        @if($errors->any())<div class="alert alert-error">{{ $errors->first() }}</div>@endif
        <div class="form-field"><label>Email</label><input name="email" type="email" value="{{ old('email') }}" required autofocus></div>
        <div class="form-field" style="margin-top:14px">
            <label>Password</label>
            <div class="password-toggle-field">
                <input name="password" type="password" required data-password-input>
                <button type="button" class="password-toggle-button" aria-label="Show password" aria-pressed="false" data-password-toggle>
                    <svg class="password-eye-icon" viewBox="0 0 24 24" aria-hidden="true" hidden data-password-eye>
                        <path d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                    <svg class="password-eye-icon" viewBox="0 0 24 24" aria-hidden="true" data-password-eye-off>
                        <path d="M3 3l18 18"/>
                        <path d="M10.7 5.2A10.7 10.7 0 0 1 12 5c6 0 9.5 7 9.5 7a17.8 17.8 0 0 1-2.8 3.7M7.5 7.3C4.4 9 2.5 12 2.5 12s3.5 7 9.5 7c1.6 0 3-.4 4.2-1"/>
                        <path d="M9.9 9.9A3 3 0 0 0 14.1 14.1"/>
                    </svg>
                </button>
            </div>
        </div>
        <label style="display:flex;align-items:center;gap:8px;margin:16px 0"><input type="checkbox" name="remember" value="1" style="width:auto"> Remember me</label>
        <button class="btn btn-primary" style="width:100%">Login</button>
        @php
            $developerCreditEnabled = ($globalSettings['developer_credit_enabled'] ?? '1') !== '0';
            $developerCreditLabel = trim($globalSettings['developer_credit_label'] ?? 'Navdak Digital');
            $developerCreditUrl = trim($globalSettings['developer_credit_url'] ?? '');
        @endphp
        @if($developerCreditEnabled && $developerCreditLabel)
            <p class="admin-login-credit">
                Admin system developed &amp; maintained by
                @if($developerCreditUrl)
                    <a href="{{ $developerCreditUrl }}" target="_blank" rel="noopener">{{ $developerCreditLabel }}</a>
                @else
                    {{ $developerCreditLabel }}
                @endif
            </p>
        @endif
    </form>
    <script>
        document.querySelectorAll('[data-password-toggle]').forEach((button) => {
            const field = button.closest('.password-toggle-field');
            const input = field?.querySelector('[data-password-input]');
            const eye = button.querySelector('[data-password-eye]');
            const eyeOff = button.querySelector('[data-password-eye-off]');

            if (!input || !eye || !eyeOff) return;

            button.addEventListener('click', () => {
                const show = input.type === 'password';
                input.type = show ? 'text' : 'password';
                eye.toggleAttribute('hidden', !show);
                eyeOff.toggleAttribute('hidden', show);
                button.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
                button.setAttribute('aria-pressed', String(show));
                input.focus({ preventScroll: true });
            });
        });
    </script>
</body>
</html>
