@extends('layouts.app')
@section('title','Team & Leadership - HUMELIX LIMITED')
@section('meta_description','Meet the Humelix engineering, technical, support and regional team structure behind safe project delivery.')
@section('content')
@include('components.page-hero',[
    'eyebrow'=>'Team & Leadership',
    'title'=>'Engineering teams, regional leads and support people.',
    'subtitle'=>'Backed by more than 500 staff across global operations, Humelix continues to expand its engineering, technical, support and regional teams.'
])

<section class="section">
    <div class="container">
        <div class="section-head">
            <span class="eyebrow">Current Team Directory</span>
            <h2 class="section-title">People who keep projects moving.</h2>
            <p class="section-sub">Humelix is structured around credible departments, field delivery, regional coordination and a safety-first service culture. The first five profiles include brief editable biographies for client review.</p>
        </div>
        <div class="grid grid-4">
            @forelse($members as $member)
                <article class="project-card" data-animate="fade-up" data-delay="{{ ($loop->index % 4) * 60 }}">
                    <div class="image-frame" style="aspect-ratio:4/5">
                        <img loading="lazy" src="{{ \App\Support\UchContent::imageUrl($member->photo_path, \App\Support\UchContent::teamImage($member->role)) }}" alt="{{ $member->name }}">
                    </div>
                    <div class="project-body">
                        <h3>{{ $member->name }}</h3>
                        <p><strong>{{ $member->role }}</strong></p>
                        <span>{{ $member->region ?: 'Global Operations' }}{{ $member->experience ? ' · '.$member->experience : '' }}</span>
                        @if($loop->iteration <= 5)
                            <p>{{ \Illuminate\Support\Str::limit($member->bio ?: 'Supports Humelix project delivery, client communication and safe engineering execution across assigned service areas.', 135) }}</p>
                            <ul class="content-summary-list">
                                <li>{{ $member->role }} focus</li>
                                <li>{{ $member->region ?: 'Regional coordination' }}</li>
                                <li>Safety-conscious project support</li>
                            </ul>
                            <a class="card-link" href="{{ route('team.show', $member) }}">Read More <span aria-hidden="true">&rarr;</span></a>
                        @endif
                    </div>
                </article>
            @empty
                <div class="empty-state">
                    <h2>Our team directory is being updated.</h2>
                    <p class="section-sub">HUMELIX LIMITED remains available for project enquiries and support.</p>
                    <a href="{{ route('contact') }}" class="btn btn-primary" style="margin-top:18px">Contact the Team</a>
                </div>
            @endforelse
        </div>
    </div>
</section>

@php
    $technicalPartnerEnabled = ($globalSettings['technical_partner_enabled'] ?? '1') !== '0';
    $technicalPartnerName = trim($globalSettings['technical_partner_name'] ?? 'Ikechukwu Prince Onyebuchi');
    $technicalPartnerTitle = trim($globalSettings['technical_partner_title'] ?? 'Website Developer & Platform Maintainer');
    $technicalPartnerBrand = trim($globalSettings['technical_partner_brand'] ?? 'Navdak Digital');
    $technicalPartnerSummary = trim($globalSettings['technical_partner_summary'] ?? 'Navdak Digital designed and developed the HUMELIX LIMITED website and admin platform.');
    $technicalPartnerAbout = trim($globalSettings['technical_partner_about'] ?? 'I support businesses with modern websites, dashboards, admin systems, automation tools and deployment-ready digital platforms. For HUMELIX LIMITED, Navdak Digital delivered the public website structure, content management dashboard, visitor analytics foundation, SEO setup and deployment support.');
    $technicalPartnerWhatsapp = trim($globalSettings['technical_partner_whatsapp'] ?? '');
    if ($technicalPartnerWhatsapp && ! \Illuminate\Support\Str::startsWith($technicalPartnerWhatsapp, ['http://', 'https://'])) {
        $technicalPartnerWhatsapp = 'https://wa.me/'.preg_replace('/\D+/', '', $technicalPartnerWhatsapp);
    }
    $technicalPartnerLinks = collect([
        ['label' => 'Portfolio', 'url' => trim($globalSettings['technical_partner_portfolio_url'] ?? '')],
        ['label' => 'WhatsApp', 'url' => $technicalPartnerWhatsapp],
        ['label' => 'GitHub', 'url' => trim($globalSettings['technical_partner_github_url'] ?? '')],
        ['label' => 'Facebook', 'url' => trim($globalSettings['technical_partner_facebook_url'] ?? '')],
        ['label' => 'Email', 'url' => filled($globalSettings['technical_partner_email'] ?? '') ? 'mailto:'.trim($globalSettings['technical_partner_email']) : ''],
        ['label' => 'LinkedIn', 'url' => trim($globalSettings['technical_partner_linkedin_url'] ?? '')],
        ['label' => trim($globalSettings['technical_partner_extra_label'] ?? ''), 'url' => trim($globalSettings['technical_partner_extra_url'] ?? '')],
    ])->filter(fn ($link) => filled($link['label']) && filled($link['url']))->values();
@endphp
@if($technicalPartnerEnabled && ($technicalPartnerName || $technicalPartnerBrand))
<section class="section technical-partner-section" style="padding-top:0">
    <div class="container">
        <article class="technical-partner-card" data-animate="fade-up">
            <div class="technical-partner-main">
                <div class="technical-partner-mark" aria-hidden="true">N</div>
                <div>
                    <span class="eyebrow">Technical Partner</span>
                    <h2>{{ $technicalPartnerName ?: $technicalPartnerBrand }}</h2>
                    <p class="technical-partner-role">{{ $technicalPartnerTitle }}{{ $technicalPartnerBrand ? ' · '.$technicalPartnerBrand : '' }}</p>
                    <p>{{ $technicalPartnerSummary }}</p>
                    <button class="technical-partner-toggle" type="button" aria-expanded="false" aria-controls="technical-partner-details" data-technical-partner-toggle>
                        See more <span aria-hidden="true">↓</span>
                    </button>
                </div>
            </div>
            <div class="technical-partner-details" id="technical-partner-details" hidden data-technical-partner-details>
                <div>
                    <h3>About the developer</h3>
                    <p>{{ $technicalPartnerAbout }}</p>
                </div>
                @if($technicalPartnerLinks->isNotEmpty())
                    <div class="technical-partner-links" aria-label="Developer contact links">
                        @foreach($technicalPartnerLinks as $link)
                            <a href="{{ $link['url'] }}" target="{{ \Illuminate\Support\Str::startsWith($link['url'], 'mailto:') ? '_self' : '_blank' }}" rel="noopener">{{ $link['label'] }}</a>
                        @endforeach
                    </div>
                @endif
                <button class="technical-partner-toggle technical-partner-less" type="button" aria-expanded="true" aria-controls="technical-partner-details" data-technical-partner-toggle>
                    See less <span aria-hidden="true">↑</span>
                </button>
            </div>
        </article>
    </div>
</section>
@endif

<section class="section" style="padding-top:0">
    <div class="container">
        <div class="contact-band" data-animate="fade-up">
            <div>
                <span class="eyebrow eyebrow-light">Global team growth</span>
                <h2>More than 500 staff supporting Humelix operations.</h2>
                <p>Humelix continues to expand engineering, technical, safety, support and regional teams to serve multiple service divisions and locations.</p>
            </div>
            <div class="contact-band-actions">
                <a class="btn btn-white" href="{{ route('careers.index') }}">Explore Careers</a>
                <a class="btn btn-ghost-light" href="{{ route('contact') }}">Contact Humelix</a>
            </div>
        </div>
    </div>
</section>
@endsection
