<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login · HUMELIX LIMITED</title>
    <link rel="icon" href="{{ asset('images/uch-favicon.svg') }}" type="image/svg+xml">
    <link rel="stylesheet" href="{{ asset('css/uch.css') }}?v=20260716b">
</head>
<body class="login-page">
    <form class="login-card" method="POST" action="{{ route('admin.login.store') }}">
        @csrf
        <div class="brand" style="margin-bottom:26px"><span class="brand-mark">H</span><span class="brand-text"><strong>HUMELIX</strong><small>LIMITED</small></span></div>
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
                    <span data-password-toggle-text>Show</span>
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
            const label = button.querySelector('[data-password-toggle-text]');

            if (!input || !label) return;

            button.addEventListener('click', () => {
                const show = input.type === 'password';
                input.type = show ? 'text' : 'password';
                label.textContent = show ? 'Hide' : 'Show';
                button.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
                button.setAttribute('aria-pressed', String(show));
                input.focus({ preventScroll: true });
            });
        });
    </script>
</body>
</html>
