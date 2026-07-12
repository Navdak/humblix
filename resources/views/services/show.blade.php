@extends('layouts.app')
@section('title',$service['title'].' - HUMELIX SYSTEMS')
@section('meta_description',$service['excerpt'])
@section('content')
@php
    $contactUrl = route('contact').'?service='.urlencode($service['slug']);
    $siteInspectionUrl = route('contact').'?service='.urlencode($service['slug'].' site inspection');
    $vendorQuoteUrl = route('contact').'?type_of_work=Vendor&service='.urlencode('Vendor / Equipment Quote');
    $whatsapp = 'https://wa.me/'.preg_replace('/\D+/', '', $globalSettings['whatsapp_number'] ?? '+2349001234567');
@endphp

<section class="service-detail-hero tone-{{ $service['accent'] }}">
    <div class="container service-detail-hero-grid">
        <div data-animate="slide-right">
            <a class="back-link" href="{{ route('services.index') }}">← All services</a>
            <span class="eyebrow">{{ $service['label'] }}</span>
            <h1>{{ $service['title'] }}</h1>
            <p>{{ $service['excerpt'] }}</p>
            <div class="service-actions">
                <a class="btn btn-primary" href="{{ $service['slug'] === 'vendor' ? $vendorQuoteUrl : $contactUrl }}">{{ $service['slug'] === 'vendor' ? 'Request Equipment Quote' : 'Request Quote' }}</a>
                @if($service['slug'] === 'vendor')
                    <a class="btn btn-white" href="{{ route('equipment.index') }}">Explore Equipment Catalogue</a>
                @else
                    <a class="btn btn-white" href="{{ $siteInspectionUrl }}">Book Site Inspection</a>
                @endif
                <button class="btn btn-outline" type="button" data-chat-open>Chat with Engineer</button>
                <a class="btn btn-whatsapp" href="{{ $whatsapp }}" target="_blank" rel="noopener">WhatsApp Us</a>
            </div>
        </div>
        <aside class="service-detail-summary" data-animate="slide-left">
            <div class="image-frame service-detail-image"><img loading="lazy" src="{{ asset($service['image']) }}" alt="{{ $service['title'] }}"></div>
            <span class="division-code">{{ $service['code'] }}</span>
            <h2>Division overview</h2>
            <p>{{ $service['overview'] }}</p>
        </aside>
    </div>
</section>

<section class="section">
    <div class="container service-detail-layout">
        <article class="service-main">
            <div class="service-block" data-animate="fade-up">
                <span class="eyebrow">Overview</span>
                <h2>Built around your site, load and long-term reliability.</h2>
                <p>{{ $service['details'] }}</p>
            </div>

            <div class="service-block" data-animate="fade-up">
                <span class="eyebrow">Key Benefits</span>
                <h2>What clients should expect from this division.</h2>
                <div class="benefit-grid">
                    @foreach($service['benefits'] as $benefit)
                        <div class="benefit-card"><span aria-hidden="true">✓</span><strong>{{ $benefit }}</strong></div>
                    @endforeach
                </div>
            </div>

            <div class="service-block" data-animate="fade-up">
                <span class="eyebrow">Services Included</span>
                <h2>Scope areas covered.</h2>
                <div class="included-grid">
                    @foreach($service['included'] as $item)
                        <div><span aria-hidden="true"></span>{{ $item }}</div>
                    @endforeach
                </div>
            </div>

            <div class="service-block" data-animate="fade-up">
                <span class="eyebrow">Process</span>
                <h2>How Humelix handles this service.</h2>
                <div class="detail-process">
                    @foreach($service['process'] as $step)
                        <div>
                            <span>{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                            <strong>{{ $step }}</strong>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="service-block" data-animate="fade-up">
                <span class="eyebrow">Suitable For</span>
                <h2>Clients and environments we can support.</h2>
                <div class="client-chip-grid">
                    @foreach($service['clients'] as $client)
                        <span>{{ $client }}</span>
                    @endforeach
                </div>
            </div>

            <div class="service-block" data-animate="fade-up">
                <span class="eyebrow">FAQs</span>
                <h2>Common questions about {{ $service['title'] }}.</h2>
                <div class="faq-list">
                    @foreach($service['faqs'] as $faq)
                        <details>
                            <summary>{{ $faq['question'] }}</summary>
                            <p>{{ $faq['answer'] }}</p>
                        </details>
                    @endforeach
                </div>
            </div>
        </article>

        <aside class="service-sidebar" data-animate="slide-left">
            <div class="service-sticky-card">
                <h2>Request this service</h2>
                <p>Share your location, building type and timeline. Humelix will recommend the right next step.</p>
                <a class="btn btn-primary btn-block" href="{{ $service['slug'] === 'vendor' ? $vendorQuoteUrl : $contactUrl }}">{{ $service['slug'] === 'vendor' ? 'Request Equipment Quote' : 'Request Quote' }}</a>
                @if($service['slug'] === 'vendor')
                    <a class="btn btn-white btn-block" href="{{ route('equipment.index') }}">Explore Equipment Catalogue</a>
                @endif
                <button class="btn btn-outline btn-block" type="button" data-chat-open>Chat with Engineer</button>
                <hr>
                <h3>Other divisions</h3>
                @foreach($services as $item)
                    @if($item['slug'] !== $service['slug'])
                        <a class="service-side-link" href="{{ route('services.show', $item['slug']) }}"><span>{{ $item['code'] }}</span>{{ $item['title'] }}</a>
                    @endif
                @endforeach
            </div>
        </aside>
    </div>
</section>

@include('partials.video-section', [
    'videos' => $relatedVideos,
    'eyebrow' => 'Service Videos',
    'title' => $service['title'].' videos',
    'subtitle' => 'Relevant field, project or demonstration videos for this division.',
])

<section class="section related-service-section">
    <div class="container">
        <div class="section-head section-head-row">
            <div>
                <span class="eyebrow">Related Projects</span>
                <h2 class="section-title">Relevant project proof where available.</h2>
            </div>
            <a class="btn btn-primary" href="{{ route('projects.index') }}">View Projects</a>
        </div>
        <div class="grid grid-3">
            @forelse($relatedProjects as $project)
                <a class="project-card" href="{{ route('projects.show', $project) }}" data-animate="fade-up" data-delay="{{ ($loop->index % 3) * 70 }}">
                    <div class="image-frame"><img loading="lazy" src="{{ \App\Support\UchContent::imageUrl($project->image_path, \App\Support\UchContent::projectImage($project->title)) }}" alt="{{ $project->title }}"></div>
                    <div class="project-body">
                        <h3>{{ $project->title }}</h3>
                        <p>{{ $project->location }}</p>
                        <span>{{ $project->system_type }}</span>
                    </div>
                </a>
            @empty
                <div class="empty-state">
                    <h2>Project examples for this division are being prepared.</h2>
                    <p class="section-sub">Speak with Humelix for relevant technical references and project capability.</p>
                    <a class="btn btn-primary" href="{{ $contactUrl }}" style="margin-top:18px">Discuss a Similar Request</a>
                </div>
            @endforelse
        </div>
    </div>
</section>

<section class="contact-band-section">
    <div class="container">
        <div class="contact-band" data-animate="fade-up">
            <div>
                <span class="eyebrow eyebrow-light">Ready when you are</span>
                <h2>Start a {{ $service['title'] }} request.</h2>
                <p>{{ $service['slug'] === 'vendor' ? 'Explore the equipment catalogue, request a vendor quote or chat with the Humelix team.' : 'Request a quote, book a site inspection or chat with the Humelix team.' }}</p>
            </div>
            <div class="contact-band-actions">
                @if($service['slug'] === 'vendor')
                    <a class="btn btn-white" href="{{ route('equipment.index') }}">Explore Equipment Catalogue</a>
                    <a class="btn btn-ghost-light" href="{{ $vendorQuoteUrl }}">Request Equipment Quote</a>
                @else
                    <a class="btn btn-white" href="{{ $contactUrl }}">Request Quote</a>
                @endif
                <a class="btn btn-whatsapp" href="{{ $whatsapp }}" target="_blank" rel="noopener">WhatsApp Us</a>
                <button class="btn btn-ghost-light" type="button" data-chat-open>Chat with Engineer</button>
            </div>
        </div>
    </div>
</section>
@endsection
