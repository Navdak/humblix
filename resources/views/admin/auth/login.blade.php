<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login · HUMELIX SYSTEMS</title>
    <link rel="icon" href="{{ asset('images/uch-favicon.svg') }}" type="image/svg+xml">
    <link rel="stylesheet" href="{{ asset('css/uch.css') }}">
</head>
<body class="login-page">
    <form class="login-card" method="POST" action="{{ route('admin.login.store') }}">
        @csrf
        <div class="brand" style="margin-bottom:26px"><span class="brand-mark">H</span><span class="brand-text"><strong>HUMELIX</strong><small>SYSTEMS</small></span></div>
        <span class="eyebrow">Private Admin Access</span>
        <h1 style="font-size:34px;margin:8px 0 8px;letter-spacing:-.04em">Sign in to dashboard</h1>
        <p class="section-sub" style="margin-bottom:24px">Manage enquiries, projects, team members, reviews, media and homepage content.</p>
        @if($errors->any())<div class="alert alert-error">{{ $errors->first() }}</div>@endif
        <div class="form-field"><label>Email</label><input name="email" type="email" value="{{ old('email') }}" required autofocus></div>
        <div class="form-field" style="margin-top:14px"><label>Password</label><input name="password" type="password" required></div>
        <label style="display:flex;align-items:center;gap:8px;margin:16px 0"><input type="checkbox" name="remember" value="1" style="width:auto"> Remember me</label>
        <button class="btn btn-primary" style="width:100%">Login</button>
    </form>
</body>
</html>
