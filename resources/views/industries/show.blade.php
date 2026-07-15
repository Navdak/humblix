@extends('layouts.app')
@section('title',$industry['title'].' - Industries - HUMELIX LIMITED')
@section('meta_description',$industry['description'])
@section('content')
@include('components.page-hero',['eyebrow'=>'Industry','title'=>$industry['title'],'subtitle'=>$industry['description']])
<section class="section">
    <div class="container phase6-detail-grid">
        <article class="card">
            <span class="eyebrow">Common Project Needs</span>
            <h2 style="margin-top:10px">How Humelix can support {{ strtolower($industry['title']) }}.</h2>
            <div class="included-grid">
                @foreach($industry['needs'] ?? [] as $need)<div><span></span>{{ $need }}</div>@endforeach
            </div>
        </article>
        <aside class="card">
            <span class="eyebrow">Relevant Services</span>
            <h2 style="margin-top:10px">Service divisions</h2>
            <div class="client-chip-grid">@foreach($services as $service)<span>{{ $service }}</span>@endforeach</div>
            <a class="btn btn-primary btn-block" style="margin-top:22px" href="{{ route('contact') }}?building={{ urlencode($industry['title']) }}">Request Consultation</a>
        </aside>
    </div>
    <div class="container" style="margin-top:48px">
        <div class="section-head section-head-row">
            <div><span class="eyebrow">Related Projects</span><h2 class="section-title">Published work in this industry.</h2></div>
            <a class="btn btn-outline" href="{{ route('projects.index') }}">View Projects</a>
        </div>
        <div class="grid grid-3">
            @forelse($projects as $project)
                <a class="project-card" href="{{ route('projects.show',$project) }}"><div class="image-frame"><img src="{{ \App\Support\UchContent::imageUrl($project->image_path, \App\Support\UchContent::projectImage($project->title)) }}" alt="{{ $project->title }}"></div><div class="project-body"><h3>{{ $project->title }}</h3><p>{{ $project->country ? $project->country.' · ' : '' }}{{ $project->location }}</p><span>{{ $project->service_division ?: $project->system_type }}</span></div></a>
            @empty
                <div class="empty-state"><h3>No published case studies for this industry yet.</h3><p class="section-sub">Contact Humelix for relevant experience and technical capability.</p></div>
            @endforelse
        </div>
    </div>
</section>
@endsection
