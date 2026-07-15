@extends('layouts.app')
@section('title',$project->title.' - HUMELIX LIMITED')
@section('meta_description',str($project->challenge ?: $project->result ?: $project->title)->limit(155))
@section('content')
@include('components.page-hero',['eyebrow'=>'Case Study','title'=>$project->title,'subtitle'=>($project->service_division ?: $project->system_type).' · '.trim(($project->country ? $project->country.' · ' : '').$project->location)])
<section class="section">
    <div class="container grid grid-2" style="align-items:start">
        <div class="image-frame" style="border-radius:18px;box-shadow:var(--shadow-lg);aspect-ratio:4/3" data-animate="slide-right"><img src="{{ \App\Support\UchContent::imageUrl($project->image_path, \App\Support\UchContent::projectImage($project->title)) }}" alt="{{ $project->title }}"></div>
        <div class="card phase6-project-summary" data-animate="slide-left">
            <span class="eyebrow">Project Details</span>
            <h2 style="margin-top:10px">{{ $project->system_type }}</h2>
            <dl>
                <div><dt>Client type</dt><dd>{{ $project->client_type ?: 'Not specified' }}</dd></div>
                <div><dt>Sector</dt><dd>{{ $project->sector ?: 'Not specified' }}</dd></div>
                <div><dt>Country / Location</dt><dd>{{ $project->country ? $project->country.' · ' : '' }}{{ $project->location }}</dd></div>
                <div><dt>Service division</dt><dd>{{ $project->service_division ?: 'Not specified' }}</dd></div>
                <div><dt>Duration</dt><dd>{{ $project->duration ?: 'Not specified' }}</dd></div>
                <div><dt>Equipment</dt><dd>{{ $project->equipment_used ?: 'Not specified' }}</dd></div>
                <div><dt>Safety controls</dt><dd>{{ $project->safety_controls ?: 'Not specified' }}</dd></div>
            </dl>
            <a href="{{ route('contact') }}?service={{ urlencode($project->service_division ?: $project->system_type) }}" class="btn btn-primary btn-block">Request Similar Project</a>
        </div>
    </div>
    <div class="container" style="margin-top:50px">
        <div class="grid grid-3">
            <div class="card" data-animate="fade-up"><span class="eyebrow">01</span><h3 style="margin-top:10px">Challenge</h3><p>{{ $project->challenge ?: 'Project challenge details are being prepared.' }}</p></div>
            <div class="card" data-animate="fade-up" data-delay="70"><span class="eyebrow">02</span><h3 style="margin-top:10px">Solution</h3><p>{{ $project->solution ?: 'Solution details are being prepared.' }}</p></div>
            <div class="card" data-animate="fade-up" data-delay="140"><span class="eyebrow">03</span><h3 style="margin-top:10px">Result</h3><p>{{ $project->result ?: $project->outcome ?: 'Outcome details are being prepared.' }}</p></div>
        </div>
        @if($project->outcome || $project->client_testimonial)
            <div class="grid grid-2" style="margin-top:24px">
                @if($project->outcome)<article class="card"><span class="eyebrow">Outcome</span><h3 style="margin-top:10px">Operational result</h3><p>{{ $project->outcome }}</p></article>@endif
                @if($project->client_testimonial)<blockquote class="card" style="margin:0"><span class="eyebrow">Client Comment</span><p style="margin-top:14px">“{{ $project->client_testimonial }}”</p></blockquote>@endif
            </div>
        @endif
        @if(is_array($project->gallery) && count($project->gallery))
            <div class="section-head section-head-row" style="margin-top:56px"><div><span class="eyebrow">Gallery</span><h2 class="section-title">Project media</h2></div></div>
            <div class="grid grid-3">@foreach($project->gallery as $image)<div class="image-frame" style="border-radius:14px"><img loading="lazy" src="{{ asset('storage/'.$image) }}" alt="{{ $project->title }} gallery image {{ $loop->iteration }}"></div>@endforeach</div>
        @endif
    </div>
</section>
@include('partials.video-section', [
    'videos' => $relatedVideos,
    'eyebrow' => 'Project Videos',
    'title' => 'Video proof for this project',
    'subtitle' => 'Published project videos linked to this case study.',
])
@include('partials.public-cta')
@endsection
