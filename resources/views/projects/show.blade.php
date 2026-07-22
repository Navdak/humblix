@extends('layouts.app')
@section('title',$project->title.' - HUMELIX LIMITED')
@section('meta_description',str($project->challenge ?: $project->result ?: $project->title)->limit(155))
@section('content')
@php
    $projectService = (string) ($project->service_division ?: $project->system_type);
    $projectType = match (true) {
        str_contains(strtolower($projectService), 'solar') => 'Solar',
        str_contains(strtolower($projectService), 'electrical') => 'Electrical',
        str_contains(strtolower($projectService), 'maintenance') => 'Maintenance',
        str_contains(strtolower($projectService), 'vendor'), str_contains(strtolower($projectService), 'equipment') => 'Vendor',
        str_contains(strtolower($projectService), 'appliance') => 'Home Appliance',
        default => 'HVAC',
    };
    $projectCtaUrl = route('contact').'?type_of_work='.urlencode($projectType).'&service='.urlencode('Similar project: '.$project->title);
@endphp
@include('components.page-hero',[
    'eyebrow'=>'Case Study',
    'title'=>$project->title,
    'subtitle'=>($project->service_division ?: $project->system_type).' · '.trim(($project->country ? $project->country.' · ' : '').$project->location)
])

<section class="section">
    <div class="container project-case-hero">
        <div class="image-frame project-case-image" data-animate="slide-right">
            <img loading="lazy" decoding="async" width="1100" height="688" src="{{ \App\Support\UchContent::imageUrl($project->image_path, \App\Support\UchContent::projectImage($project->title)) }}" alt="{{ $project->title }}">
        </div>
        <aside class="card phase6-project-summary project-case-summary" data-animate="slide-left">
            <span class="eyebrow">Project Overview</span>
            <h2 style="margin-top:10px">{{ $project->system_type }}</h2>
            <p>{{ $project->challenge ? \Illuminate\Support\Str::limit($project->challenge, 180) : 'A Humelix case study prepared around practical site delivery, technical review and client support.' }}</p>
            <dl>
                <div><dt>Client type</dt><dd>{{ $project->client_type ?: 'Not specified' }}</dd></div>
                <div><dt>Sector</dt><dd>{{ $project->sector ?: 'Not specified' }}</dd></div>
                <div><dt>Country / Location</dt><dd>{{ $project->country ? $project->country.' · ' : '' }}{{ $project->location }}</dd></div>
                <div><dt>Service division</dt><dd>{{ $project->service_division ?: 'Not specified' }}</dd></div>
                <div><dt>Duration</dt><dd>{{ $project->duration ?: 'Not specified' }}</dd></div>
            </dl>
            <a href="{{ $projectCtaUrl }}" class="btn btn-primary btn-block">Request Similar Project</a>
        </aside>
    </div>

    <div class="container" style="margin-top:50px">
        <div class="section-head section-head-row">
            <div>
                <span class="eyebrow">Case Study</span>
                <h2 class="section-title">From challenge to delivered outcome.</h2>
            </div>
            <a class="btn btn-outline" href="{{ route('projects.index') }}">View More Projects</a>
        </div>
        <div class="grid grid-3 project-case-grid">
            <article class="card" data-animate="fade-up">
                <span class="eyebrow">01 · Client Challenge</span>
                <h3 style="margin-top:10px">What needed to be solved</h3>
                <p>{!! nl2br(e($project->challenge ?: 'Project challenge details are being prepared.')) !!}</p>
            </article>
            <article class="card" data-animate="fade-up" data-delay="70">
                <span class="eyebrow">02 · Solution Delivered</span>
                <h3 style="margin-top:10px">How Humelix responded</h3>
                <p>{!! nl2br(e($project->solution ?: 'Solution details are being prepared.')) !!}</p>
            </article>
            <article class="card" data-animate="fade-up" data-delay="140">
                <span class="eyebrow">03 · Result / Outcome</span>
                <h3 style="margin-top:10px">The operational result</h3>
                <p>{!! nl2br(e($project->result ?: $project->outcome ?: 'Outcome details are being prepared.')) !!}</p>
            </article>
        </div>

        <div class="grid grid-2 project-proof-grid" style="margin-top:24px">
            <article class="card" data-animate="fade-up">
                <span class="eyebrow">Equipment / Materials</span>
                <h3 style="margin-top:10px">What supported the work</h3>
                <p>{!! nl2br(e($project->equipment_used ?: 'Equipment and material details are being prepared.')) !!}</p>
            </article>
            <article class="card" data-animate="fade-up" data-delay="70">
                <span class="eyebrow">Safety Approach</span>
                <h3 style="margin-top:10px">How the site was controlled</h3>
                <p>{!! nl2br(e($project->safety_controls ?: 'Safety controls and site-protection notes are being prepared.')) !!}</p>
            </article>
        </div>

        <div class="project-trust-strip" data-animate="fade-up">
            <div><strong>Technical review</strong><span>Site requirements, service scope and practical constraints are reviewed before recommendations.</span></div>
            <div><strong>Safe delivery</strong><span>Work is aligned with safety controls, clean execution and client-site protection.</span></div>
            <div><strong>Handover support</strong><span>Completed work is closed with guidance, next steps and aftercare awareness.</span></div>
        </div>

        @if($project->outcome || $project->client_testimonial)
            <div class="grid grid-2" style="margin-top:24px">
                @if($project->outcome)<article class="card"><span class="eyebrow">Extended Outcome</span><h3 style="margin-top:10px">Operational result</h3><p>{!! nl2br(e($project->outcome)) !!}</p></article>@endif
                @if($project->client_testimonial)<blockquote class="card" style="margin:0"><span class="eyebrow">Client Comment</span><p style="margin-top:14px">“{{ $project->client_testimonial }}”</p></blockquote>@endif
            </div>
        @endif
        @if(is_array($project->gallery) && count($project->gallery))
            <div class="section-head section-head-row" style="margin-top:56px"><div><span class="eyebrow">Gallery</span><h2 class="section-title">Project media</h2></div></div>
            <div class="grid grid-3">@foreach($project->gallery as $image)<div class="image-frame" style="border-radius:14px"><img loading="lazy" decoding="async" width="800" height="500" src="{{ asset('storage/'.$image) }}" alt="{{ $project->title }} gallery image {{ $loop->iteration }}"></div>@endforeach</div>
        @endif
    </div>
</section>

@include('partials.video-section', [
    'videos' => $relatedVideos,
    'eyebrow' => 'Project Videos',
    'title' => 'Video proof for this project',
    'subtitle' => 'Published project videos linked to this case study.',
])

<section class="contact-band-section">
    <div class="container">
        <div class="contact-band" data-animate="fade-up">
            <div>
                <span class="eyebrow eyebrow-light">Need a similar result?</span>
                <h2>Request a similar {{ strtolower($projectType) }} project consultation.</h2>
                <p>Share your location, building type and project condition. Humelix will review the right next step.</p>
            </div>
            <div class="contact-band-actions">
                <a class="btn btn-white" href="{{ $projectCtaUrl }}">Request Similar Project</a>
                <a class="btn btn-ghost-light" href="{{ route('contact') }}">Contact Humelix</a>
                <button class="btn btn-ghost-light" type="button" data-chat-open>Chat with Engineer</button>
            </div>
        </div>
    </div>
</section>
@endsection
