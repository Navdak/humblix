<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    @php
        $seo = \App\Support\SeoMeta::forCurrentPage([
            'page_key' => trim($__env->yieldContent('seo_key')) ?: null,
            'title' => trim($__env->yieldContent('title', 'HUMELIX SYSTEMS - HVAC, Solar, Electrical & Maintenance Solutions')),
            'description' => trim($__env->yieldContent('meta_description', 'Humelix Systems provides HVAC, solar, electrical, maintenance and equipment solutions for residential, commercial and industrial clients.')),
            'structured_data' => $structuredData ?? [],
            'image' => $seoImage ?? null,
        ]);
    @endphp
    <title>{{ $seo['title'] }}</title>
    <meta name="description" content="{{ $seo['description'] }}">
    @if($seo['keywords'])<meta name="keywords" content="{{ $seo['keywords'] }}">@endif
    <link rel="canonical" href="{{ $seo['canonical'] }}">
    <meta name="robots" content="{{ $seo['robots'] }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="HUMELIX SYSTEMS">
    <meta property="og:title" content="{{ $seo['og_title'] }}">
    <meta property="og:description" content="{{ $seo['og_description'] }}">
    <meta property="og:url" content="{{ $seo['canonical'] }}">
    <meta property="og:image" content="{{ $seo['og_image'] }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seo['twitter_title'] }}">
    <meta name="twitter:description" content="{{ $seo['twitter_description'] }}">
    <meta name="twitter:image" content="{{ $seo['twitter_image'] }}">
    <meta name="theme-color" content="#0A192F">
    <link rel="icon" href="{{ asset('images/uch-favicon.svg') }}" type="image/svg+xml">
    <link rel="stylesheet" href="{{ asset('css/uch.css') }}">
    @foreach($seo['structured_data'] as $schema)
        <script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @endforeach
</head>
<body>
<a class="skip-link" href="#main-content">Skip to content</a>
<header class="site-header" data-site-header>
    <div class="container navbar">
        <a href="{{ route('home') }}" class="brand" aria-label="HUMELIX SYSTEMS home">
            <span class="brand-mark" aria-hidden="true">H</span>
            <span class="brand-text"><strong>HUMELIX</strong><small>SYSTEMS</small></span>
        </a>
        <nav class="nav-links" aria-label="Primary navigation">
            <a class="{{ request()->routeIs('about') ? 'is-active' : '' }}" href="{{ route('about') }}">About</a>
            <a class="{{ request()->routeIs('services.*') ? 'is-active' : '' }}" href="{{ route('services.index') }}">Services</a>
            <a class="{{ request()->routeIs('industries.*') || request()->routeIs('sectors.*') ? 'is-active' : '' }}" href="{{ route('industries.index') }}">Industries</a>
            <a class="{{ request()->routeIs('projects.*') ? 'is-active' : '' }}" href="{{ route('projects.index') }}">Projects</a>
            <a class="{{ request()->routeIs('safety') || request()->routeIs('safety.*') ? 'is-active' : '' }}" href="{{ route('safety') }}">Safety</a>
            <a class="{{ request()->routeIs('team.*') ? 'is-active' : '' }}" href="{{ route('team.index') }}">Team</a>
            <a class="{{ request()->routeIs('branches.*') ? 'is-active' : '' }}" href="{{ route('branches.index') }}">Branches</a>
            <a class="{{ request()->routeIs('articles.*') ? 'is-active' : '' }}" href="{{ route('articles.index') }}">Resources</a>
            <a class="{{ request()->routeIs('careers.*') ? 'is-active' : '' }}" href="{{ route('careers.index') }}">Careers</a>
            <a class="{{ request()->routeIs('contact*') ? 'is-active' : '' }}" href="{{ route('contact') }}">Contact</a>
        </nav>
        <a class="btn btn-primary nav-cta" href="{{ route('contact') }}">Request Service</a>
        <button class="mobile-menu-btn" type="button" aria-label="Open navigation" aria-expanded="false" aria-controls="mobile-navigation" data-menu-toggle>
            <span></span><span></span><span></span>
        </button>
    </div>
    <nav id="mobile-navigation" class="mobile-navigation" aria-label="Mobile navigation" hidden data-mobile-menu>
        <div class="container mobile-navigation-inner">
            <a href="{{ route('home') }}">Home</a><a href="{{ route('about') }}">About</a>
            <a href="{{ route('services.index') }}">Services</a><a href="{{ route('industries.index') }}">Industries</a>
            <a href="{{ route('projects.index') }}">Projects</a><a href="{{ route('safety') }}">Safety</a>
            <a href="{{ route('team.index') }}">Team</a><a href="{{ route('branches.index') }}">Branches</a>
            <a href="{{ route('articles.index') }}">Resources</a><a href="{{ route('careers.index') }}">Careers</a>
            <a href="{{ route('contact') }}">Contact</a><a class="btn btn-primary" href="{{ route('contact') }}">Request Service</a>
        </div>
    </nav>
</header>
<main id="main-content">@yield('content')</main>
@include('partials.video-modal')
@include('partials.footer')
@include('partials.chat')
<button class="back-to-top" type="button" aria-label="Back to top" data-back-to-top hidden>
    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m6 14 6-6 6 6M12 8v10"/></svg>
</button>
<script src="{{ asset('js/uch.js') }}" defer></script>
</body>
</html>
