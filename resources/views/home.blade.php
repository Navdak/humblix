@extends('layouts.app')
@section('content')
@php
    $fallbackProjects = collect([
        ['title' => 'Industrial Plant — Lagos', 'system' => 'HVAC System Installation', 'image' => 'images/generated/projects/project-industrial-plant-neutral.jpg'],
        ['title' => 'Office Complex — Abuja', 'system' => 'VRF System Installation', 'image' => 'images/generated/projects/project-office-complex-neutral.jpg'],
        ['title' => 'High-Rise Building — Dubai', 'system' => 'Centralized Cooling System', 'image' => 'images/generated/projects/project-high-rise-cooling-neutral.jpg'],
        ['title' => 'Warehouse — Port Harcourt', 'system' => 'Ventilation & Cooling System', 'image' => 'images/generated/projects/project-warehouse-ventilation-neutral.jpg'],
    ]);
    $fallbackReviews = collect([
        ['comment' => 'Professional team, excellent service and top-quality installation. The project was delivered safely and on schedule.', 'client_name' => 'Facility Manager', 'location' => 'Lagos'],
        ['comment' => 'Clear communication from assessment through commissioning. Their aftercare has been dependable.', 'client_name' => 'Operations Lead', 'location' => 'Abuja'],
        ['comment' => 'A responsive engineering partner who understood the demands of our facility.', 'client_name' => 'Property Director', 'location' => 'Port Harcourt'],
    ]);
    $projectFallbackImages = [
        'images/generated/projects/project-industrial-plant-neutral.jpg',
        'images/generated/projects/project-office-complex-neutral.jpg',
        'images/generated/projects/project-high-rise-cooling-neutral.jpg',
        'images/generated/projects/project-warehouse-ventilation-neutral.jpg',
    ];
@endphp

<section class="hero">
    <div class="hero-media" role="img" aria-label="Humelix Systems engineer servicing engineering equipment"></div>
    <div class="hero-shade"></div>
    <div class="container hero-grid">
        <div class="hero-copy" data-animate="fade-up">
            <h1>{{ $globalSettings['hero_headline'] ?? 'Engineering Comfort. Powering Reliability.' }}</h1>
            <p>{{ $globalSettings['hero_subtext'] ?? 'HVAC, solar, electrical, maintenance and equipment solutions for residential, commercial and industrial clients worldwide.' }}</p>
            <div class="hero-actions">
                <a href="{{ route('contact') }}" class="btn btn-primary">Get a Quote</a>
                <button type="button" class="btn btn-white" data-chat-open>Chat Now</button>
                <a href="https://wa.me/{{ preg_replace('/\D+/', '', $globalSettings['whatsapp_number'] ?? '+2349001234567') }}" class="btn btn-whatsapp" target="_blank" rel="noopener">WhatsApp Us</a>
            </div>
        </div>
        <div class="hero-proof" data-animate="slide-left" data-delay="140">
            <strong>Precision Power. Flawless Comfort.</strong>
            <p>From site assessment to commissioning and aftercare, every stage is handled with technical discipline.</p>
            <div class="hero-proof-row"><span>Industrial</span><span>Commercial</span><span>Residential</span></div>
        </div>
    </div>
</section>

<section class="trust-strip" aria-label="HUMELIX SYSTEMS capabilities">
    <div class="container trust-inner">
        @foreach($trustBadges as $badge)
            <div class="trust-item" data-animate="fade-up" data-delay="{{ $loop->index * 60 }}">
                <span class="line-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M12 3v3m0 12v3M3 12h3m12 0h3M5.6 5.6l2.1 2.1m8.6 8.6 2.1 2.1m0-12.8-2.1 2.1m-8.6 8.6-2.1 2.1"/><circle cx="12" cy="12" r="4"/></svg></span>
                <div><strong>{{ $badge['label'] }}</strong><small>{{ $badge['text'] }}</small></div>
            </div>
        @endforeach
    </div>
</section>

<section class="section services-section">
    <div class="container">
        <div class="section-head" data-animate="fade-up"><span class="eyebrow">Our Core Services</span><h2 class="section-title">Engineering systems built around your building.</h2><p class="section-sub">HVAC, solar, electrical, maintenance, Humelix Vendor / Equipment and home installation support for environments where performance matters.</p></div>
        <div class="grid service-grid">
            @foreach($services as $service)
                <a class="service-card" href="{{ route('services.show',$service['slug']) }}" data-animate="fade-up" data-delay="{{ ($loop->index % 3) * 80 }}">
                    <span class="line-icon line-icon-blue" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M5 19V9l7-5 7 5v10M9 19v-6h6v6M3 19h18"/></svg></span>
                    <h3>{{ $service['title'] }}</h3><p>{{ $service['excerpt'] }}</p><span class="text-link">{{ $service['slug'] === 'vendor' ? 'View Vendor / Equipment' : 'View service' }} <span aria-hidden="true">→</span></span>
                </a>
            @endforeach
        </div>
    </div>
</section>

<section class="section sector-section">
    <div class="container sector-panel">
        <div class="sector-copy" data-animate="slide-right"><span class="eyebrow eyebrow-light">Industry Expertise</span><h2 class="section-title">What type of building do you manage?</h2><p>Specialized planning, equipment selection and maintenance strategies for every operating environment.</p><a href="{{ route('industries.index') }}" class="btn btn-white">Explore Industries</a></div>
        <div class="sector-grid" data-animate="fade-up">
            @foreach($sectors as $sector)<a class="sector-chip" href="{{ route('sectors.show',$sector['slug']) }}"><span class="line-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M4 20V8l8-4v16m0-10h8v10M2 20h20M7 11h2m-2 4h2m7-1h2"/></svg></span><strong>{{ $sector['title'] }}</strong></a>@endforeach
        </div>
    </div>
</section>

<section class="section projects-section">
    <div class="container">
        <div class="section-head section-head-row" data-animate="fade-up"><div><span class="eyebrow">Featured Projects</span><h2 class="section-title">Field work that earns confidence.</h2></div><a href="{{ route('projects.index') }}" class="btn btn-outline">View All Projects</a></div>
        <div class="grid project-grid">
            @forelse($projects as $project)
                <a class="project-card" href="{{ route('projects.show',$project) }}" data-animate="fade-up" data-delay="{{ $loop->index * 70 }}"><div class="image-frame"><img src="{{ \App\Support\UchContent::imageUrl($project->image_path, $projectFallbackImages[$loop->index % count($projectFallbackImages)]) }}" alt="{{ $project->title }}"></div><div class="project-body"><h3>{{ $project->title }}</h3><p>{{ $project->location }}</p><span>{{ $project->system_type }}</span></div></a>
            @empty
                @foreach($fallbackProjects as $project)
                    <a class="project-card" href="{{ route('projects.index') }}" data-animate="fade-up" data-delay="{{ $loop->index * 70 }}"><div class="image-frame"><img src="{{ asset($project['image']) }}" alt="{{ $project['title'] }}"></div><div class="project-body"><h3>{{ $project['title'] }}</h3><span>{{ $project['system'] }}</span></div></a>
                @endforeach
            @endforelse
        </div>
    </div>
</section>

@include('partials.video-section', [
    'videos' => $featuredVideos,
    'eyebrow' => 'Video Highlights',
    'title' => 'Field Work & Product Highlights',
    'subtitle' => 'Featured Humelix videos from field work, projects, product demos and service activity.',
])

<section class="section why-section">
    <div class="container why-layout">
        <div class="why-intro" data-animate="slide-right"><span class="eyebrow">Why Choose HUMELIX SYSTEMS?</span><h2 class="section-title">Reliable engineering. Clear accountability.</h2><p>Our teams combine practical field knowledge with responsive service and disciplined safety standards.</p><a href="{{ route('about') }}" class="text-link">Learn about our approach <span aria-hidden="true">→</span></a></div>
        <div class="why-grid">
            @foreach($whyChoose as $item)<div class="feature-row" data-animate="fade-up" data-delay="{{ ($loop->index % 2) * 70 }}"><span class="feature-number">0{{ $loop->iteration }}</span><div><h3>{{ $item['title'] }}</h3><p>{{ $item['text'] }}</p></div></div>@endforeach
        </div>
    </div>
</section>

<section class="section reviews-section">
    <div class="container">
        <div class="section-head section-head-light" data-animate="fade-up"><span class="eyebrow eyebrow-light">Client Reviews</span><h2 class="section-title">What our clients say.</h2></div>
        <div class="grid review-grid">
            @foreach(($reviews->isNotEmpty() ? $reviews : $fallbackReviews) as $review)
                @php $comment = is_array($review) ? $review['comment'] : $review->comment; $name = is_array($review) ? $review['client_name'] : $review->client_name; $location = is_array($review) ? $review['location'] : $review->location; @endphp
                <blockquote class="testimonial" data-animate="scale-in" data-delay="{{ $loop->index * 80 }}"><div class="stars" aria-label="5 out of 5 stars">★★★★★</div><p>“{{ $comment }}”</p><footer><strong>{{ $name }}</strong><span>{{ $location }}</span></footer></blockquote>
            @endforeach
        </div>
    </div>
</section>

<section class="section founder-section">
    <div class="container founder-block">
        <div class="founder-copy" data-animate="slide-right"><span class="eyebrow">Founder’s Message</span><h2 class="section-title">Engineering leadership grounded in the field.</h2><p>{{ $globalSettings['founder_snapshot'] ?? 'At HUMELIX SYSTEMS, our mission is simple: deliver safe, precise and reliable engineering solutions that improve comfort, power reliability and operational performance.' }}</p><div class="founder-signature"><strong>UGOCHUKWU HUMBLE CHIEMELA</strong><span>Founder & Lead Engineer</span></div><a href="{{ route('founder') }}" class="btn btn-primary">Read Founder Profile</a></div>
        <div class="founder-visual" data-animate="slide-left"><img src="{{ asset('images/generated/careers/careers-engineers-inspecting-systems.jpg') }}" alt="UGOCHUKWU HUMBLE CHIEMELA, Founder and Lead Engineer"></div>
    </div>
</section>

<section class="section contact-band-section">
    <div class="container contact-band" data-animate="fade-up"><div><span class="eyebrow eyebrow-light">Let’s Work Together</span><h2>Need HVAC, solar, electrical or maintenance support?</h2><p>Tell us about your site and receive a clear next step from our team.</p></div><div class="contact-band-actions"><a href="{{ route('contact') }}" class="btn btn-white">Request Quote</a><button type="button" class="btn btn-ghost-light" data-chat-open>Chat Now</button><a href="https://wa.me/{{ preg_replace('/\D+/', '', $globalSettings['whatsapp_number'] ?? '+2349001234567') }}" class="btn btn-ghost-light" target="_blank" rel="noopener">WhatsApp</a></div></div>
</section>
@endsection
