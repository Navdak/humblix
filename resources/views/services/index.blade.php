@extends('layouts.app')
@section('title','Engineering Service Divisions - HUMELIX LIMITED')
@section('meta_description','Explore Humelix Limited service divisions for HVAC, solar, electrical maintenance, equipment supply and home appliance installation.')
@section('content')
@php
    $whatsapp = 'https://wa.me/'.preg_replace('/\D+/', '', $globalSettings['whatsapp_number'] ?? '+2349001234567');
    $servicesHeroImageUrl = $hero?->imageUrl() ?: asset('images/generated/services/service-hvac-installation.jpg');
    $servicesHeroEyebrow = $hero?->eyebrow ?: 'Humelix Services';
    $servicesHeroTitle = $hero?->title ?: 'Engineering Service Divisions';
    $servicesHeroSubtitle = $hero?->subtitle ?: 'Humelix provides HVAC, solar, electrical, maintenance, vendor/equipment and home appliance installation solutions for residential, commercial and industrial environments.';
@endphp

<section class="service-gateway-hero" style="--service-hero-image:url('{{ $servicesHeroImageUrl }}')">
    <div class="container service-gateway-grid">
        <div class="service-gateway-copy" data-animate="slide-right">
            <span class="eyebrow">{{ $servicesHeroEyebrow }}</span>
            <h1>{{ $servicesHeroTitle }}</h1>
            <p>{{ $servicesHeroSubtitle }}</p>
            <div class="service-actions">
                <a class="btn btn-primary" href="{{ route('contact') }}?service={{ urlencode('service request') }}">Request Service</a>
                <a class="btn btn-white" href="{{ route('contact') }}?service={{ urlencode('site inspection') }}">Book Site Inspection</a>
                <button class="btn btn-outline" type="button" data-chat-open>Chat with Engineer</button>
            </div>
        </div>
        <div class="service-gateway-panel" data-animate="slide-left">
            <strong>One service pathway</strong>
            <p>From technical consultation to site assessment, quotation, installation or supply, testing and aftercare.</p>
            <div class="service-mini-list">
                @foreach($services as $service)
                    <a href="{{ route('services.show', $service['slug']) }}"><span>{{ $service['code'] }}</span>{{ $service['title'] }}</a>
                @endforeach
            </div>
        </div>
    </div>
</section>

<section class="section service-division-section">
    <div class="container">
        <div class="section-head">
            <span class="eyebrow">Service Divisions</span>
            <h2 class="section-title">Specialist teams, clear scopes and practical engineering delivery.</h2>
            <p class="section-sub">Choose the division that matches your project, site issue or equipment need. Each route leads to a focused service page with scope, process, FAQs and enquiry CTAs.</p>
        </div>
        <div class="division-grid">
            @foreach($services as $service)
                <article class="division-card tone-{{ $service['accent'] }}" data-animate="fade-up" data-delay="{{ ($loop->index % 3) * 70 }}">
                    <div class="image-frame division-card-image"><img loading="eager" src="{{ asset($service['image']) }}" alt="{{ $service['title'] }}"></div>
                    <div class="division-card-top">
                        <span class="division-code">{{ $service['code'] }}</span>
                        <span class="division-label">{{ $service['label'] }}</span>
                    </div>
                    <h3>{{ $service['title'] }}</h3>
                    <p>{{ $service['excerpt'] }}</p>
                    <ul class="content-summary-list">
                        @foreach(array_slice($service['included'], 0, 3) as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                    <div class="division-included" aria-label="Key included services">
                        @foreach(array_slice($service['included'], 0, 5) as $item)
                            <span>{{ $item }}</span>
                        @endforeach
                    </div>
                    <a class="card-link" href="{{ route('services.show', $service['slug']) }}">{{ $service['slug'] === 'vendor' ? 'Read More / Explore Equipment' : 'Read More' }} <span aria-hidden="true">→</span></a>
                </article>
            @endforeach
        </div>
    </div>
</section>

<section class="section service-process-section">
    <div class="container">
        <div class="section-head section-head-row">
            <div>
                <span class="eyebrow">How Humelix Works</span>
                <h2 class="section-title">A disciplined service process from first contact to aftercare.</h2>
            </div>
            <a class="btn btn-primary" href="{{ route('contact') }}?service={{ urlencode('consultation') }}">Request Consultation</a>
        </div>
        <div class="process-ladder">
            @foreach($process as $step)
                <div class="process-step" data-animate="fade-up" data-delay="{{ ($loop->index % 4) * 60 }}">
                    <span>{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                    <strong>{{ $step }}</strong>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="contact-band-section">
    <div class="container">
        <div class="contact-band" data-animate="fade-up">
            <div>
                <span class="eyebrow eyebrow-light">Start a service request</span>
                <h2>Need HVAC, solar, electrical or installation support?</h2>
                <p>Share your location, site type and timeline. Humelix will guide the next practical step.</p>
            </div>
            <div class="contact-band-actions">
                <a class="btn btn-white" href="{{ route('contact') }}?service={{ urlencode('consultation') }}">Request Consultation</a>
                <a class="btn btn-whatsapp" href="{{ $whatsapp }}" target="_blank" rel="noopener">WhatsApp Us</a>
                <a class="btn btn-ghost-light" href="{{ route('contact') }}">Contact Humelix</a>
            </div>
        </div>
    </div>
</section>
@endsection
