@extends('layouts.app')
@section('title',$article->title.' — HUMELIX SYSTEMS')
@section('meta_description',$article->excerpt)
@section('content')
@include('components.page-hero',['eyebrow'=>'Resource','title'=>$article->title,'subtitle'=>$article->excerpt])
<section class="section">
    <div class="container grid grid-2" style="grid-template-columns:minmax(0,1fr) minmax(260px,330px);align-items:start">
        <article class="card prose" data-animate="fade-up">
            <div class="image-frame" style="margin-bottom:24px">
                <img src="{{ \App\Support\UchContent::imageUrl($article->featured_image_path, 'images/generated/safety/safety-toolbox-talks.jpg') }}" alt="{{ $article->title }}">
            </div>
            {!! $article->content !!}
            <div class="service-actions" style="margin-top:28px">
                <a class="btn btn-primary" href="{{ route('contact') }}?service={{ urlencode('Resource enquiry: '.$article->title) }}">Ask Humelix</a>
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
@include('partials.public-cta')
@endsection
