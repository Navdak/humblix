<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') · HUMELIX LIMITED</title>
    <link rel="icon" href="{{ asset('images/brand/humelix-favicon-32.png') }}" sizes="32x32" type="image/png">
    <link rel="apple-touch-icon" href="{{ asset('images/brand/humelix-apple-touch-icon.png') }}">
    <link rel="stylesheet" href="{{ asset('css/uch.css') }}?v=20260717b">
    @vite('resources/js/app.js')
    @stack('head')
</head>
<body class="admin-body">
<div class="admin-shell">
    <aside class="admin-sidebar" data-admin-sidebar aria-label="Admin navigation">
        <div class="admin-sidebar-head">
            <a href="{{ route('admin.dashboard') }}" class="admin-brand" aria-label="HUMELIX LIMITED Admin home"><span class="brand-mark" aria-hidden="true"><img src="{{ asset('images/brand/humelix-logo-mark.png') }}" alt=""></span><span><strong>HUMELIX</strong><small>LIMITED</small></span></a>
            <button type="button" class="admin-sidebar-close" aria-label="Close navigation" data-admin-menu-toggle><span>×</span></button>
        </div>
        <nav class="admin-nav">
            @php
                $adminNavGroups = [
                    'Overview' => [
                        ['admin.dashboard','admin.dashboard','dashboard','Dashboard','dashboard'],
                    ],
                    'Operations' => [
                        ['admin.enquiries.*','admin.enquiries.index','enquiries','Enquiries','enquiries'],
                        ['admin.projects.*','admin.projects.index','projects','Projects','projects'],
                        ['admin.branches.*','admin.branches.index','branches','Branches','branches'],
                    ],
                    'Services & Catalogue' => [
                        ['admin.services.*','admin.services.index','services','Services','services'],
                        ['admin.equipment.*','admin.equipment.index','equipment','Equipment','equipment'],
                        ['admin.videos.*','admin.videos.index','videos','Videos','videos'],
                    ],
                    'Content' => [
                        ['admin.page-heroes.*','admin.page-heroes.index','page-heroes','Page Heroes','page_heroes'],
                        ['admin.articles.*','admin.articles.index','articles','Resources / Articles','articles'],
                        ['admin.media.*','admin.media.index','media','Media Library','media'],
                        ['admin.reviews.*','admin.reviews.index','reviews','Reviews','reviews'],
                        ['admin.safety.*','admin.safety.index','safety','Safety','safety'],
                    ],
                    'People' => [
                        ['admin.team.*','admin.team.index','team','Team','team'],
                        ['admin.jobs.*','admin.jobs.index','careers','Careers','jobs'],
                    ],
                    'Configuration' => [
                        ['admin.users.*','admin.users.index','users','Users & Roles','users'],
                        ['admin.roles.*','admin.roles.index','users','Role Permissions','users'],
                        ['admin.settings.*','admin.settings.edit','settings','Site Settings','settings'],
                        ['admin.seo-settings.*','admin.seo-settings.index','seo','SEO Settings','seo'],
                    ],
                ];
            @endphp
            @foreach($adminNavGroups as $groupLabel => $items)
                @php $visibleItems = collect($items)->filter(fn ($item) => auth()->user()?->canManage($item[4]) ?? false); @endphp
                @continue($visibleItems->isEmpty())
                <div class="admin-nav-group">
                    <span class="admin-nav-label">{{ $groupLabel }}</span>
                    @foreach($visibleItems as [$pattern,$routeName,$icon,$label])
                        <a href="{{ route($routeName) }}" class="{{ request()->routeIs($pattern) ? 'is-active' : '' }}"><x-admin-icon :name="$icon"/><span>{{ $label }}</span></a>
                    @endforeach
                </div>
            @endforeach
        </nav>
        <div class="admin-sidebar-foot">
            <div class="admin-user-mini">@include('admin.partials.avatar',['user'=>auth()->user()])<div><strong>{{ auth()->user()?->displayName() ?? 'Admin User' }}</strong><small>{{ auth()->user()?->roleLabel() ?? 'Administrator' }}</small></div></div>
            <form method="POST" action="{{ route('admin.logout') }}">@csrf<button type="submit" class="admin-logout"><x-admin-icon name="logout"/> Logout</button></form>
        </div>
    </aside>
    <div class="admin-overlay" hidden data-admin-overlay></div>
    <div class="admin-workspace">
        <header class="admin-topbar">
            <div class="admin-topbar-left"><button type="button" class="admin-menu-button" aria-label="Open navigation" aria-expanded="false" data-admin-menu-toggle><x-admin-icon name="menu"/></button><nav class="admin-breadcrumb" aria-label="Breadcrumb"><a href="{{ route('admin.dashboard') }}">Home</a><span>/</span><strong>@yield('page_title','Dashboard')</strong></nav></div>
            <div class="admin-topbar-actions">
                <div class="admin-search-wrap">
                    <label class="admin-search"><x-admin-icon name="search"/><input type="search" placeholder="Search admin…" aria-label="Search admin" data-admin-search></label>
                    <div class="admin-search-results" hidden data-admin-search-results></div>
                </div>
                <div class="admin-notification-wrap"
                    data-admin-notifications
                    data-endpoint="{{ route('admin.notifications.index') }}"
                    data-read-endpoint-template="{{ url('/admin/notifications/__ID__/read') }}"
                    data-read-all-endpoint="{{ route('admin.notifications.read-all') }}"
                    data-current-module="{{ request()->routeIs('admin.enquiries.index') ? 'enquiries' : '' }}"
                    data-current-route="{{ Route::currentRouteName() }}">
                    <button type="button" class="admin-icon-button admin-notification-toggle" aria-label="Notifications" aria-expanded="false" data-notification-toggle>
                        <x-admin-icon name="bell"/>
                        <span class="notification-dot" hidden data-notification-dot aria-label="New notifications"></span>
                        <span class="notification-count" hidden data-notification-count></span>
                    </button>
                    <section class="admin-notification-panel" hidden data-notification-panel aria-label="Admin notifications">
                        <header>
                            <div>
                                <strong>Notifications</strong>
                                <small data-notification-summary>Checking for updates...</small>
                            </div>
                            <div class="admin-notification-panel-actions">
                                <button type="button" data-notification-refresh>Refresh</button>
                                <button type="button" data-notification-read-all>Mark all read</button>
                            </div>
                        </header>
                        <div class="admin-notification-list" data-notification-list>
                            <div class="admin-notification-empty">Loading notifications...</div>
                        </div>
                    </section>
                </div>
                <div class="admin-top-user">@include('admin.partials.avatar',['user'=>auth()->user()])<span><strong>{{ auth()->user()?->displayName() ?? 'Admin User' }}</strong><small>{{ auth()->user()?->roleLabel() ?? 'Administrator' }}</small></span></div>
                <a class="btn btn-outline admin-view-site" href="{{ route('home') }}" target="_blank" rel="noopener" aria-label="View website"><x-admin-icon name="external"/><span class="admin-view-site-label">View Website</span></a>
            </div>
        </header>
        <main class="admin-main">
            <div class="admin-page-heading"><div><h1>@yield('page_title','Dashboard')</h1><p>@yield('page_subtitle','Manage HUMELIX LIMITED operations and content.')</p></div>@yield('page_actions')</div>
            <div class="admin-live-banner" hidden data-live-list-banner>
                <div>
                    <strong data-live-list-message>Fresh updates are available.</strong>
                    <span>No page refresh will happen until you choose to update the list.</span>
                </div>
                <button type="button" class="btn btn-primary" data-live-list-refresh>Update list</button>
            </div>
            @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
            @if($errors->any())<div class="alert alert-error"><strong>Fix these fields:</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
            @yield('content')
        </main>
    </div>
</div>
<div class="admin-toast-stack" data-admin-toast-stack aria-live="polite" aria-atomic="false"></div>
<script src="{{ asset('js/admin.js') }}" defer></script>
@stack('scripts')
</body>
</html>
