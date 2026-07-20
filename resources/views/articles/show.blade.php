@extends('layouts.app')
@section('title',$article->title.' — HUMELIX LIMITED')
@section('meta_description',$article->excerpt)
@section('content')
@include('components.page-hero',['eyebrow'=>'Resource','title'=>$article->title,'subtitle'=>$article->excerpt])
@php([$contentBeforeVideo, $contentAfterVideo] = $article->sanitizedContentSegmentsForVideo())
<section class="section">
    <div class="container grid grid-2" style="grid-template-columns:minmax(0,1fr) minmax(260px,330px);align-items:start">
        <article class="card prose" data-animate="fade-up">
            <div class="image-frame" style="margin-bottom:24px">
                <img src="{{ \App\Support\UchContent::imageUrl($article->featured_image_path, 'images/generated/safety/safety-toolbox-talks.jpg') }}" alt="{{ $article->title }}">
            </div>
            <span class="badge">{{ $article->categoryLabel() }}</span>
            @if($article->hasPdfAttachment())
                <div class="admin-note" style="margin:18px 0 24px">
                    <strong>Downloadable guide available:</strong>
                    <p style="margin:8px 0 14px">This resource includes a PDF version for offline reading, sharing, or field reference.</p>
                    <a class="btn btn-primary" href="{{ $article->pdfUrl() }}" target="_blank" rel="noopener" download>Download Full Guide (PDF)</a>
                </div>
            @endif
            @if($article->hasArticleVideo() && $article->articleVideoPlacement() === 'after_intro')
                @include('articles.partials.article-video', ['article' => $article])
            @endif
            {!! $contentBeforeVideo !!}
            @if($article->hasArticleVideo() && $article->articleVideoPlacement() === 'middle')
                @include('articles.partials.article-video', ['article' => $article])
            @endif
            {!! $contentAfterVideo !!}
            @if($article->hasArticleVideo() && $article->articleVideoPlacement() === 'end')
                @include('articles.partials.article-video', ['article' => $article])
            @endif
            <div class="service-actions" style="margin-top:28px">
                <a class="btn btn-primary" href="{{ route('contact') }}?service={{ urlencode('Resource enquiry: '.$article->title) }}">Ask Humelix</a>
                @if($article->hasPdfAttachment())<a class="btn btn-white" href="{{ $article->pdfUrl() }}" target="_blank" rel="noopener" download>Download PDF</a>@endif
                <a class="btn btn-white" href="{{ route('articles.index') }}">Back to Resources</a>
            </div>
        </article>
        <aside class="card" data-animate="slide-left">
            <h3>Related Links</h3>
            @forelse($article->relatedLinks as $link)
                <a class="card-link" style="display:flex;margin:12px 0" href="{{ $link->url }}" target="_blank" rel="noopener">{{ $link->link_text }} <span>&rarr;</span></a>
            @empty
                <p>No related links added.</p>
            @endforelse
            <hr style="border:0;border-top:1px solid var(--line);margin:24px 0">
            <h3>Latest Resources</h3>
            @foreach($latestArticles as $latest)
                <a class="card-link" style="display:flex;margin:12px 0" href="{{ route('articles.show',$latest) }}">{{ $latest->title }}</a>
            @endforeach
        </aside>
    </div>
</section>
<section class="section" style="padding-top:0">
    <div class="container">
        @include('partials.newsletter-signup')
    </div>
</section>
@if($relatedArticles->isNotEmpty())
<section class="section" style="padding-top:0">
    <div class="container">
        <div class="section-heading">
            <span class="eyebrow">Related Resources</span>
            <h2>Continue reading practical Humelix guides.</h2>
            <p class="section-sub">More resources connected to this topic and nearby engineering concerns.</p>
        </div>
        <div class="grid grid-3">
            @foreach($relatedArticles as $related)
                <a class="project-card" href="{{ route('articles.show', $related) }}" data-animate="fade-up" data-delay="{{ ($loop->index % 3) * 70 }}">
                    <div class="image-frame"><img loading="lazy" src="{{ \App\Support\UchContent::imageUrl($related->featured_image_path, 'images/generated/safety/safety-toolbox-talks.jpg') }}" alt="{{ $related->title }}"></div>
                    <div class="project-body">
                        <span class="badge">{{ $related->categoryLabel() }}</span>
                        <h3 style="margin-top:12px">{{ $related->title }}</h3>
                        <p>{{ $related->excerpt }}</p>
                        <span class="card-link">Read More <span>&rarr;</span></span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif
@include('partials.public-cta')
@endsection
