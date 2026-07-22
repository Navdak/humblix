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
                        <img loading="lazy" decoding="async" width="800" height="1000" src="{{ \App\Support\UchContent::imageUrl($member->photo_path, \App\Support\UchContent::teamImage($member->role)) }}" alt="{{ $member->name }}">
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
    $technicalPartnerImage = trim($globalSettings['technical_partner_image_path'] ?? 'images/generated/careers/careers-office-admin-culture.jpg');
    $technicalPartnerSummary = trim($globalSettings['technical_partner_summary'] ?? '') ?: 'I design and maintain modern business websites, admin dashboards and digital platforms that are clean, scalable and easy for teams to manage.';
    $technicalPartnerAbout = trim($globalSettings['technical_partner_about'] ?? '') ?: 'I am a website developer and platform maintainer focused on building reliable business systems, admin dashboards, automation-ready workflows and deployment-ready digital platforms. For HUMELIX LIMITED, Navdak Digital delivered the public website structure, editable admin dashboard, visitor analytics foundation, SEO setup, generated visual assets and Render preview deployment workflow.';
    $technicalPartnerPortfolioUrl = trim($globalSettings['technical_partner_portfolio_url'] ?? '');
    $technicalPartnerWhatsapp = trim($globalSettings['technical_partner_whatsapp'] ?? '');
    if ($technicalPartnerWhatsapp && ! \Illuminate\Support\Str::startsWith($technicalPartnerWhatsapp, ['http://', 'https://'])) {
        $technicalPartnerWhatsapp = 'https://wa.me/'.preg_replace('/\D+/', '', $technicalPartnerWhatsapp);
    }
    $technicalPartnerEmail = trim($globalSettings['technical_partner_email'] ?? '');
    $technicalPartnerLinks = collect([
        ['label' => 'Visit My Portfolio', 'short' => 'Portfolio', 'icon' => 'portfolio', 'url' => $technicalPartnerPortfolioUrl, 'featured' => true],
        ['label' => 'WhatsApp', 'short' => 'WhatsApp', 'icon' => 'whatsapp', 'url' => $technicalPartnerWhatsapp],
        ['label' => 'GitHub', 'short' => 'GitHub', 'icon' => 'github', 'url' => trim($globalSettings['technical_partner_github_url'] ?? '')],
        ['label' => 'Facebook', 'short' => 'Facebook', 'icon' => 'facebook', 'url' => trim($globalSettings['technical_partner_facebook_url'] ?? '')],
        ['label' => 'Email', 'short' => 'Email', 'icon' => 'email', 'url' => $technicalPartnerEmail ? 'mailto:'.$technicalPartnerEmail : ''],
        ['label' => 'LinkedIn', 'short' => 'LinkedIn', 'icon' => 'linkedin', 'url' => trim($globalSettings['technical_partner_linkedin_url'] ?? '')],
        ['label' => trim($globalSettings['technical_partner_extra_label'] ?? ''), 'short' => trim($globalSettings['technical_partner_extra_label'] ?? ''), 'icon' => 'link', 'url' => trim($globalSettings['technical_partner_extra_url'] ?? '')],
    ])->filter(fn ($link) => filled($link['label']) && filled($link['url']))->values();
@endphp
@if($technicalPartnerEnabled && ($technicalPartnerName || $technicalPartnerBrand))
<section class="section technical-partner-section">
    <div class="container">
        <div class="section-head">
            <span class="eyebrow">Technical Partner</span>
            <h2 class="section-title">Website and platform development support.</h2>
            <p class="section-sub">A dedicated partner profile for the developer maintaining the HUMELIX LIMITED website and admin platform.</p>
        </div>
        <article class="technical-partner-card">
            <div class="technical-partner-main">
                <div class="technical-partner-photo">
                    <img loading="lazy" decoding="async" width="800" height="1000" src="{{ \App\Support\UchContent::imageUrl($technicalPartnerImage, 'images/generated/careers/careers-office-admin-culture.jpg') }}" alt="{{ $technicalPartnerName ?: $technicalPartnerBrand }}">
                    <span>Technical Partner</span>
                </div>
                <div class="technical-partner-copy">
                    <span class="eyebrow">Technical Partner</span>
                    <h3>{{ $technicalPartnerName ?: $technicalPartnerBrand }}</h3>
                    <p class="technical-partner-role">{{ $technicalPartnerTitle }}{{ $technicalPartnerBrand ? ' · '.$technicalPartnerBrand : '' }}</p>
                    <p class="technical-partner-brief">{{ $technicalPartnerSummary }}</p>
                    <button class="technical-partner-toggle" type="button" aria-expanded="false" aria-controls="technical-partner-details" data-technical-partner-toggle>
                        Read More <span aria-hidden="true">↓</span>
                    </button>
                </div>
            </div>
            <div class="technical-partner-details" id="technical-partner-details" hidden data-technical-partner-details>
                <div>
                    <h3>About the developer</h3>
                    <p>{{ $technicalPartnerAbout }}</p>
                </div>
                @if($technicalPartnerLinks->isNotEmpty())
                    <div class="technical-partner-connect">
                        <h3>Connect with the developer</h3>
                        <div class="technical-partner-links" aria-label="Developer contact links">
                        @foreach($technicalPartnerLinks as $link)
                            <a class="{{ $link['featured'] ?? false ? 'is-featured' : '' }}" href="{{ $link['url'] }}" target="{{ \Illuminate\Support\Str::startsWith($link['url'], 'mailto:') ? '_self' : '_blank' }}" rel="noopener" aria-label="{{ $link['label'] }}">
                                @switch($link['icon'])
                                    @case('portfolio')
                                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M10 6h4M4 9h16M6 20h12a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-2.2A2 2 0 0 0 14 5h-4a2 2 0 0 0-1.8 1H6a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2Zm6-9v3"/></svg>
                                        @break
                                    @case('whatsapp')
                                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5.5 19.5 6.7 16A7.7 7.7 0 1 1 10 18.3l-4.5 1.2Z"/><path d="M9.2 8.7c.2-.4.4-.4.7-.4h.5c.2 0 .4 0 .5.4l.6 1.4c.1.3.1.5-.1.7l-.4.5c.7 1.2 1.6 2.1 2.9 2.8l.6-.7c.2-.2.4-.2.7-.1l1.4.7c.3.1.4.3.4.6v.4c0 .4-.2.7-.5.9-.5.3-1.3.4-2.5 0-2.7-.9-5-3.2-5.8-5.7-.3-1-.2-1.8.1-2.3Z"/></svg>
                                        @break
                                    @case('github')
                                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9 19c-4 1.2-4-2-5.5-2.5M15 22v-3.4a3 3 0 0 0-.8-2.3c2.8-.3 5.8-1.4 5.8-6.2A4.8 4.8 0 0 0 18.7 7 4.5 4.5 0 0 0 18.6 4s-1-.3-3.3 1.2a11.4 11.4 0 0 0-6 0C7 3.7 6 4 6 4a4.5 4.5 0 0 0-.1 3A4.8 4.8 0 0 0 4.6 10c0 4.8 3 5.9 5.8 6.2a3 3 0 0 0-.9 2.3V22"/></svg>
                                        @break
                                    @case('facebook')
                                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M14 8h2V4h-2a5 5 0 0 0-5 5v3H6v4h3v6h4v-6h3l1-4h-4V9a1 1 0 0 1 1-1Z"/></svg>
                                        @break
                                    @case('email')
                                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 6h16v12H4z"/><path d="m4 7 8 6 8-6"/></svg>
                                        @break
                                    @case('linkedin')
                                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 9h4v11H4zM6 6.5A2 2 0 1 0 6 2a2 2 0 0 0 0 4.5ZM11 9h4v1.7c.6-1 1.6-2 3.4-2 2.5 0 4 1.7 4 5.1V20h-4v-5.6c0-1.4-.5-2.3-1.7-2.3-1 0-1.5.6-1.8 1.2-.1.2-.1.6-.1.9V20h-4V9Z"/></svg>
                                        @break
                                    @default
                                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M10 13a5 5 0 0 0 7 0l2-2a5 5 0 0 0-7-7l-1 1M14 11a5 5 0 0 0-7 0l-2 2a5 5 0 0 0 7 7l1-1"/></svg>
                                @endswitch
                                <span>{{ $link['featured'] ?? false ? $link['label'] : $link['short'] }}</span>
                            </a>
                        @endforeach
                        </div>
                    </div>
                @endif
                <button class="technical-partner-toggle technical-partner-less" type="button" aria-expanded="true" aria-controls="technical-partner-details" data-technical-partner-toggle>
                    See Less <span aria-hidden="true">↑</span>
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
