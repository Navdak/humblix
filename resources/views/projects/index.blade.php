@extends('layouts.app')
@section('title','Projects - HUMELIX LIMITED')
@section('meta_description','Explore HUMELIX LIMITED case studies across HVAC, solar, electrical, maintenance, vendor and home installation work.')
@section('content')
@include('components.page-hero',['eyebrow'=>'Projects','title'=>'Engineering case studies and project proof.','subtitle'=>'Selected work across industrial, commercial, residential, institutional and public-sector environments.'])
<section class="section">
    <div class="container">
        <div class="section-head section-head-row">
            <div><span class="eyebrow">Project Library</span><h2 class="section-title">Case studies with outcomes and service context.</h2></div>
            <a href="{{ route('contact') }}" class="btn btn-primary">Request Consultation</a>
        </div>
        <form class="phase6-filter-bar" method="GET" action="{{ route('projects.index') }}">
            <label><span>Service</span><select name="service"><option value="">All services</option>@foreach($serviceDivisions as $service)<option value="{{ $service }}" @selected(request('service') === $service)>{{ $service }}</option>@endforeach</select></label>
            <label><span>Country</span><select name="country"><option value="">All countries</option>@foreach($countries as $country)<option value="{{ $country }}" @selected(request('country') === $country)>{{ $country }}</option>@endforeach</select></label>
            <button class="btn btn-outline">Filter</button>
            @if(request()->hasAny(['service','country']))<a class="btn btn-white" href="{{ route('projects.index') }}">Clear</a>@endif
        </form>
        <div class="grid grid-3">
            @forelse($projects as $project)
                <a class="project-card" href="{{ route('projects.show',$project) }}" data-animate="fade-up" data-delay="{{ ($loop->index % 3) * 70 }}">
                    <div class="image-frame"><img loading="lazy" decoding="async" width="800" height="500" src="{{ \App\Support\UchContent::imageUrl($project->image_path, \App\Support\UchContent::projectImage($project->title)) }}" alt="{{ $project->title }}"></div>
                    <div class="project-body">
                        <span class="badge">{{ $project->service_division ?: $project->system_type }}</span>
                        <h3 style="margin-top:12px">{{ $project->title }}</h3>
                        <p>{{ $project->country ? $project->country.' · ' : '' }}{{ $project->location }}</p>
                        <span>{{ $project->outcome ?: $project->result }}</span>
                    </div>
                </a>
            @empty
                <div class="empty-state"><h2>Our published project library is being updated.</h2><p class="section-sub">Speak with our team for relevant project references and technical capability.</p><a href="{{ route('contact') }}" class="btn btn-primary" style="margin-top:18px">Discuss Your Project</a></div>
            @endforelse
        </div>
        <div style="margin-top:28px">{{ $projects->links() }}</div>
    </div>
</section>
@include('partials.public-cta')
@endsection
