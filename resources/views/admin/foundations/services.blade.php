@extends('layouts.admin')
@section('title','Services')
@section('page_title','Services')
@section('page_subtitle','Blueprint service-division foundation for HUMELIX operations.')
@section('page_actions')<a class="btn btn-outline" href="{{ route('services.index') }}" target="_blank" rel="noopener"><x-admin-icon name="external"/> View Public Services</a>@endsection
@section('content')
<section class="admin-data-grid">
    <article class="admin-panel">
        <header class="admin-panel-head"><div><h2>Five service divisions</h2><p>These are the final public Humelix divisions managed as structured website content today.</p></div><span class="admin-panel-chip">Foundation</span></header>
        <div class="admin-module-grid">
            @foreach($divisions as $division)
                <div class="admin-module-card">
                    <span><x-admin-icon name="{{ str_contains($division, 'Vendor') ? 'equipment' : 'services' }}"/></span>
                    <strong>{{ $division }}</strong>
                    <small>{{ number_format((int) ($projectCounts[$division] ?? 0)) }} linked project(s)</small>
                </div>
            @endforeach
        </div>
    </article>
    <article class="admin-panel">
        <header class="admin-panel-head"><div><h2>What this page controls now</h2><p>Phase 8 keeps this as a clean foundation rather than inventing fake service CRUD.</p></div></header>
        <ul class="admin-checklist">
            <li><span class="status-dot is-good"></span> Confirms the five public service divisions.</li>
            <li><span class="status-dot is-good"></span> Links service operations with projects, enquiries, equipment and videos.</li>
            <li><span class="status-dot is-warn"></span> Full service division editor remains a later enhancement.</li>
        </ul>
        <div class="quick-action-grid" style="margin-top:14px">
            <a href="{{ route('admin.projects.create') }}"><x-admin-icon name="projects"/>Add Project</a>
            <a href="{{ route('admin.equipment.create') }}"><x-admin-icon name="equipment"/>Add Equipment</a>
            <a href="{{ route('admin.videos.create') }}"><x-admin-icon name="videos"/>Add Video</a>
        </div>
    </article>
</section>
@endsection
