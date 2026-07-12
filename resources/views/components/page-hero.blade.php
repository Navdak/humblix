@php($heroImage = $image ?? \App\Support\UchContent::pageHeroImage(request()->route()?->getName(), $title ?? ''))
<section class="page-hero" style="--page-hero-image:url('{{ asset($heroImage) }}')">
    <div class="container page-hero-inner" data-animate="fade-up">
        <span class="eyebrow">{{ $eyebrow ?? 'HUMELIX SYSTEMS' }}</span>
        <h1>{{ $title ?? '' }}</h1>
        @if(!empty($subtitle))<p>{{ $subtitle }}</p>@endif
        <nav class="breadcrumbs" aria-label="Breadcrumb"><a href="{{ route('home') }}">Home</a><span aria-hidden="true">/</span><span>{{ $eyebrow ?? 'Page' }}</span></nav>
    </div>
</section>
