@extends('layouts.app')
@section('title','Careers - HUMELIX LIMITED')
@section('meta_description','Explore published job openings and career opportunities at HUMELIX LIMITED.')
@section('content')
@include('components.page-hero',['eyebrow'=>'Careers','title'=>'Build practical engineering work with Humelix.','subtitle'=>'As Humelix continues to expand globally, we continue to welcome disciplined engineers, technicians, safety-conscious installers, project coordinators and support professionals.'])
<section class="section">
    <div class="container">
        <div class="grid grid-3">
            <article class="card" data-animate="fade-up"><div class="image-frame page-card-image"><img loading="lazy" decoding="async" width="800" height="450" src="{{ asset('images/generated/careers/careers-technicians-working.jpg') }}" alt="Humelix technicians working"></div><span class="eyebrow">Why Humelix</span><h2>Field-led engineering culture.</h2><p>Backed by more than 500 staff across global operations, work is grounded in practical site discipline, client communication, maintenance thinking and safe execution.</p></article>
            <article class="card" data-animate="fade-up" data-delay="70"><div class="image-frame page-card-image"><img loading="lazy" decoding="async" width="800" height="450" src="{{ asset('images/generated/careers/careers-engineers-inspecting-systems.jpg') }}" alt="Humelix engineers inspecting systems"></div><span class="eyebrow">Opportunities</span><h2>Technical and support paths.</h2><p>HVAC, solar, electrical, maintenance, project support and customer-service roles can all be represented in the job board.</p></article>
            <article class="card" data-animate="fade-up" data-delay="140"><div class="image-frame page-card-image"><img loading="lazy" decoding="async" width="800" height="450" src="{{ asset('images/generated/careers/careers-training-safety-briefing.jpg') }}" alt="Humelix safety briefing"></div><span class="eyebrow">Safety First</span><h2>Professional site standards.</h2><p>Humelix expects PPE discipline, risk awareness, clean workmanship, documentation and respectful client-site conduct.</p></article>
        </div>
    </div>
</section>
<section class="section phase6-section">
    <div class="container">
        <div class="section-head section-head-row">
            <div><span class="eyebrow">Open Roles</span><h2 class="section-title">Current published openings.</h2></div>
            <a href="{{ route('contact') }}?service={{ urlencode('Careers') }}" class="btn btn-outline">General Career Enquiry</a>
        </div>
        <div class="phase6-list">
            @forelse($jobs as $job)
                <article class="phase6-list-card" data-animate="fade-up">
                    <div>
                        <span class="eyebrow">{{ $job->department ?: 'Humelix Team' }}</span>
                        <h3>{{ $job->title }}</h3>
                        <p>{{ $job->location ?: 'Location to be confirmed' }}{{ $job->employment_type ? ' · '.$job->employment_type : '' }}</p>
                    </div>
                    <div class="phase6-list-body">
                        @if($job->description)<p>{{ $job->description }}</p>@endif
                        @if($job->requirements)<p><strong>Requirements:</strong> {{ $job->requirements }}</p>@endif
                        @if($job->closing_date)<span class="badge">Closes {{ $job->closing_date->format('M j, Y') }}</span>@endif
                    </div>
                    <div class="phase6-list-actions">
                        @if($job->application_url)
                            <a class="btn btn-primary" href="{{ $job->application_url }}" target="_blank" rel="noopener">Apply</a>
                        @elseif($job->application_email)
                            <a class="btn btn-primary" href="mailto:{{ $job->application_email }}?subject={{ rawurlencode('Application: '.$job->title) }}">Apply by Email</a>
                        @else
                            <a class="btn btn-primary" href="{{ route('contact') }}?service={{ urlencode('Careers: '.$job->title) }}">Apply / Ask</a>
                        @endif
                    </div>
                </article>
            @empty
                <div class="empty-state">
                    <h2>No open roles are published right now.</h2>
                    <p class="section-sub">The careers module is active. Add verified job openings from admin when recruitment is approved.</p>
                    <a href="{{ route('contact') }}?service={{ urlencode('Careers') }}" class="btn btn-primary" style="margin-top:18px">Send Career Enquiry</a>
                </div>
            @endforelse
        </div>
    </div>
</section>
@include('partials.public-cta')
@endsection
