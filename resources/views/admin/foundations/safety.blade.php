@extends('layouts.admin')
@section('title','Safety')
@section('page_title','Safety')
@section('page_subtitle','Safety Centre admin foundation for controlled work, PPE and compliance messaging.')
@section('page_actions')<a class="btn btn-outline" href="{{ route('safety') }}" target="_blank" rel="noopener"><x-admin-icon name="external"/> View Safety Centre</a>@endsection
@section('content')
<section class="admin-data-grid">
    <article class="admin-panel">
        <header class="admin-panel-head"><div><h2>Safety Centre foundation</h2><p>The public Safety Centre is currently content-driven and static.</p></div><span class="admin-panel-chip">Phase 8 foundation</span></header>
        <div class="admin-module-grid">
            <div class="admin-module-card"><span><x-admin-icon name="safety"/></span><strong>Risk Assessment</strong><small>Public safety module</small></div>
            <div class="admin-module-card"><span><x-admin-icon name="safety"/></span><strong>PPE Compliance</strong><small>Public safety module</small></div>
            <div class="admin-module-card"><span><x-admin-icon name="safety"/></span><strong>Electrical Isolation</strong><small>Public safety module</small></div>
            <div class="admin-module-card"><span><x-admin-icon name="videos"/></span><strong>{{ number_format($publishedSafetyVideoCount) }} / {{ number_format($safetyVideoCount) }} videos</strong><small>Published safety videos / all safety videos</small></div>
        </div>
    </article>
    <article class="admin-panel">
        <header class="admin-panel-head"><div><h2>Safety admin notes</h2><p>Designed for Safety Officer access without adding fake incident workflows.</p></div></header>
        <ul class="admin-checklist">
            <li><span class="status-dot is-good"></span> Safety Officer role can reach this foundation page.</li>
            <li><span class="status-dot is-good"></span> Safety-related videos are managed in the Video Library.</li>
            <li><span class="status-dot is-warn"></span> Audits, incident logs and compliance records are not implemented in Phase 8.</li>
        </ul>
        <div class="quick-action-grid" style="margin-top:14px">
            <a href="{{ route('admin.videos.create') }}"><x-admin-icon name="videos"/>Add Safety Video</a>
            <a href="{{ route('admin.media.index') }}"><x-admin-icon name="media"/>Media Library</a>
        </div>
    </article>
</section>
@endsection
