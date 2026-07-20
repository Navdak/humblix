@extends('layouts.app')
@section('title','Resources - HUMELIX LIMITED')
@section('meta_description','HVAC, solar, electrical, safety, maintenance, vendor and company resources from HUMELIX LIMITED.')
@section('content')
@include('components.page-hero',[
    'eyebrow'=>'Resources',
    'title'=>'Practical engineering guides, maintenance advice and safety resources.',
    'subtitle'=>'Publish HVAC, solar, electrical, safety, maintenance, vendor and company resources for clients planning reliable building systems.'
])
<section class="section" style="padding-bottom:0">
    <div class="container">
        <div class="empty-state">
            <h2>Looking for field videos?</h2>
            <p class="section-sub">The Humelix Video Library houses project, product, safety and service demonstration videos.</p>
            <a class="btn btn-primary" href="{{ route('videos.index') }}" style="margin-top:18px">Open Video Library</a>
        </div>
        <div style="margin-top:28px">
            @include('partials.newsletter-signup')
        </div>
        <div class="service-actions" style="justify-content:center;gap:10px;flex-wrap:wrap;margin-top:28px">
            <a class="btn {{ $activeCategory ? 'btn-white' : 'btn-primary' }}" href="{{ route('articles.index') }}">All Resources</a>
            @foreach($categories as $value => $label)
                <a class="btn {{ $activeCategory === $value ? 'btn-primary' : 'btn-white' }}" href="{{ route('articles.index', ['category' => $value]) }}">{{ $label }}</a>
            @endforeach
        </div>
    </div>
</section>
<section class="section">
    <div class="container">
        <div class="grid grid-3">
            @forelse($articles as $article)
                <a class="project-card" href="{{ route('articles.show',$article) }}" data-animate="fade-up" data-delay="{{ ($loop->index % 3) * 70 }}">
                    <div class="image-frame"><img loading="lazy" src="{{ \App\Support\UchContent::imageUrl($article->featured_image_path, 'images/generated/safety/safety-toolbox-talks.jpg') }}" alt="{{ $article->title }}"></div>
                    <div class="project-body">
                        <span class="badge">{{ $article->categoryLabel() }}</span>
                        <h3 style="margin-top:12px">{{ $article->title }}</h3>
                        <p>{{ $article->excerpt }}</p>
                        <ul class="content-summary-list">
                            <li>Practical planning guidance</li>
                            <li>Safety and maintenance awareness</li>
                            <li>Read the full resource for details</li>
                        </ul>
                        <span class="card-link">Read More <span>&rarr;</span></span>
                    </div>
                </a>
            @empty
                <div class="empty-state">
                    <h2>{{ $activeCategory ? 'No '.$categories[$activeCategory].' resources yet.' : 'New resources are being prepared.' }}</h2>
                    <p class="section-sub">Contact HUMELIX LIMITED directly if you need guidance for a current HVAC, solar, electrical, maintenance, safety or vendor challenge.</p>
                    @if($activeCategory)<a class="btn btn-primary" href="{{ route('articles.index') }}" style="margin-top:18px">View All Resources</a>@endif
                </div>
            @endforelse
        </div>
        <div style="margin-top:28px">{{ $articles->links() }}</div>
    </div>
</section>
@include('partials.public-cta')
@endsection
