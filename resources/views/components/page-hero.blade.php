@php
    $pageHero = \App\Models\PageHero::resolve($heroKey ?? \App\Models\PageHero::keyFromRequest());
    $heroEyebrow = $pageHero?->eyebrow ?: ($eyebrow ?? 'HUMELIX LIMITED');
    $heroTitle = $pageHero?->title ?: ($title ?? '');
    $heroSubtitle = $pageHero?->subtitle ?: ($subtitle ?? '');
    $heroImage = $image ?? $pageHero?->imagePath() ?? \App\Support\UchContent::pageHeroImage(request()->route()?->getName(), $heroTitle);
    $heroImageUrl = \App\Support\UchContent::imageUrl($heroImage);
@endphp
<section class="page-hero" style="--page-hero-image:url('{{ $heroImageUrl }}')">
    <div class="container page-hero-inner" data-animate="fade-up">
        <span class="eyebrow">{{ $heroEyebrow }}</span>
        <h1>{{ $heroTitle }}</h1>
        @if(!empty($heroSubtitle))<p>{{ $heroSubtitle }}</p>@endif
        <nav class="breadcrumbs" aria-label="Breadcrumb"><a href="{{ route('home') }}">Home</a><span aria-hidden="true">/</span><span>{{ $heroEyebrow ?: 'Page' }}</span></nav>
    </div>
</section>
