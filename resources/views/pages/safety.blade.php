@extends('layouts.app')
@section('title','Safety Centre - Humelix Systems')
@section('meta_description','Explore Humelix Systems safety-first approach to HVAC, solar, electrical, maintenance, equipment and home appliance projects.')
@section('content')
@php
    $maintenanceContact = route('contact').'?service='.urlencode('Maintenance');
    $electricalContact = route('contact').'?service='.urlencode('Electrical');
@endphp

<section class="safety-hero" style="--safety-hero-image:url('{{ asset('images/generated/safety/safety-ppe.jpg') }}')">
    <div class="container safety-hero-grid">
        <div class="safety-hero-copy" data-animate="slide-right">
            <span class="eyebrow">Safety Centre</span>
            <h1>Safety Centre</h1>
            <p class="safety-hero-lead">Safe engineering delivery for HVAC, solar, electrical, maintenance, equipment and home appliance projects.</p>
            <p>At Humelix Systems, safety comes before every installation. From HVAC systems to solar mounting, electrical wiring, equipment supply and home appliance installation, our teams follow controlled work procedures designed to protect people, buildings and the environment.</p>
            <div class="service-actions">
                <a class="btn btn-primary" href="{{ route('contact') }}?service={{ urlencode('HVAC') }}">Request Service</a>
                <a class="btn btn-white" href="{{ $electricalContact }}">Report a Safety Concern</a>
                <button class="btn btn-outline" type="button" data-chat-open>Talk to Humelix</button>
            </div>
        </div>
        <aside class="safety-hero-panel" data-animate="slide-left">
            <span class="safety-seal">SAFE</span>
            <h2>Controlled work procedures.</h2>
            <p>No job is successful unless workers, clients and property are protected.</p>
            <div class="safety-panel-list">
                <span>Risk-aware planning</span>
                <span>Inspection and testing</span>
                <span>Documented handover</span>
            </div>
        </aside>
    </div>
</section>

<section class="section safety-pillars-section">
    <div class="container">
        <div class="section-head">
            <span class="eyebrow">Safety Pillars</span>
            <h2 class="section-title">Three standards guide every Humelix site decision.</h2>
            <p class="section-sub">The Safety Centre keeps our approach clear: protect people, deliver quality, and build a practical compliance culture.</p>
        </div>
        <div class="grid grid-3">
            @foreach($pillars as $pillar)
                <article class="safety-pillar-card" data-animate="fade-up" data-delay="{{ ($loop->index % 3) * 70 }}">
                    <span class="safety-mark">{{ $pillar['mark'] }}</span>
                    <h3>{{ $pillar['title'] }}</h3>
                    <strong>{{ $pillar['message'] }}</strong>
                    <p>{{ $pillar['description'] }}</p>
                </article>
            @endforeach
        </div>
    </div>
</section>

<section class="section safety-statement-section">
    <div class="container">
        <div class="safety-statement-card" data-animate="fade-up">
            <span class="eyebrow">Safety Statement</span>
            <blockquote>At Humelix Systems, safety comes before every installation. From HVAC systems to solar mounting, electrical wiring, equipment supply and home appliance installation, our teams follow controlled work procedures designed to protect people, buildings and the environment.</blockquote>
        </div>
    </div>
</section>

<section class="section safety-framework-section">
    <div class="container">
        <div class="section-head section-head-row">
            <div>
                <span class="eyebrow">Safety Framework</span>
                <h2 class="section-title">Practical controls for real engineering work.</h2>
            </div>
            <a class="btn btn-primary" href="{{ $maintenanceContact }}">Request Site Inspection</a>
        </div>
        <div class="safety-module-grid">
            @foreach($modules as $module)
                <article class="safety-module-card" id="{{ $module['slug'] }}" data-animate="fade-up" data-delay="{{ ($loop->index % 4) * 55 }}">
                    <div class="image-frame safety-module-image"><img loading="lazy" src="{{ asset($module['image'] ?? \App\Support\UchContent::safetyImage($module['title'])) }}" alt="{{ $module['title'] }}"></div>
                    <div class="safety-module-top">
                        <span>{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        <a href="{{ route('safety.topic', $module['slug']) }}">Learn more</a>
                    </div>
                    <h3>{{ $module['title'] }}</h3>
                    <p>{{ $module['description'] }}</p>
                </article>
            @endforeach
        </div>
    </div>
</section>

<section class="section controlled-work-section">
    <div class="container">
        <div class="controlled-work-panel">
            <div class="controlled-work-copy" data-animate="slide-right">
                <span class="eyebrow eyebrow-light">Controlled Work Procedure</span>
                <h2>From site risk review to documented handover.</h2>
                <p>Humelix keeps safety practical: plan the site, brief the team, control hazards, execute carefully, test the work and hand it over clearly.</p>
            </div>
            <div class="controlled-work-steps" data-animate="slide-left">
                @foreach($process as $step)
                    <div><span>{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span><strong>{{ $step }}</strong></div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<section class="section division-safety-section">
    <div class="container">
        <div class="section-head">
            <span class="eyebrow">Across Service Divisions</span>
            <h2 class="section-title">Safety adapts to the work being delivered.</h2>
            <p class="section-sub">Different services carry different risks, so Humelix applies safety thinking to each division instead of treating safety as generic paperwork.</p>
        </div>
        <div class="division-safety-grid">
            @foreach($divisionSafety as $item)
                <article class="division-safety-card" data-animate="fade-up" data-delay="{{ ($loop->index % 3) * 60 }}">
                    <h3>{{ $item['title'] }}</h3>
                    <p>{{ $item['text'] }}</p>
                </article>
            @endforeach
        </div>
    </div>
</section>

@include('partials.video-section', [
    'videos' => $safetyVideos,
    'eyebrow' => 'Safety Videos',
    'title' => 'Safety activities and controlled work highlights',
    'subtitle' => 'Published Humelix safety videos appear here when available.',
])

<section class="contact-band-section">
    <div class="container">
        <div class="contact-band safety-cta-band" data-animate="fade-up">
            <div>
                <span class="eyebrow eyebrow-light">Safety-led project planning</span>
                <h2>Need a site inspection or safety-aware service request?</h2>
                <p>Share your project details, upload photos, or chat with a Humelix engineer.</p>
            </div>
            <div class="contact-band-actions">
                <a class="btn btn-white" href="{{ $maintenanceContact }}">Request Site Inspection</a>
                <a class="btn btn-ghost-light" href="{{ route('contact') }}">Contact Humelix</a>
                <a class="btn btn-white" href="{{ route('contact') }}?service={{ urlencode('Maintenance') }}">Upload Project Details</a>
                <button class="btn btn-ghost-light" type="button" data-chat-open>Chat with Engineer</button>
            </div>
        </div>
    </div>
</section>
@endsection
