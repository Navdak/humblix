@extends('layouts.app')
@section('title','Humelix Video Library - HUMELIX SYSTEMS')
@section('meta_description','Watch HUMELIX SYSTEMS field work, project, product, safety and service demonstration videos.')
@section('content')
@include('components.page-hero',['eyebrow'=>'Video Library','title'=>'Humelix Video Library','subtitle'=>'Short field, project, product, safety and service demonstration videos from HUMELIX SYSTEMS.'])
@if($featuredVideos->isNotEmpty())
    @include('partials.video-section',['videos'=>$featuredVideos,'eyebrow'=>'Featured Videos','title'=>'Field Work & Product Highlights','subtitle'=>'Featured published videos selected by the Humelix team.','cta'=>false])
@endif
<section class="section {{ $featuredVideos->isNotEmpty() ? 'phase6-section' : '' }}">
    <div class="container">
        <div class="section-head section-head-row">
            <div><span class="eyebrow">Browse Videos</span><h2 class="section-title">Published video library.</h2></div>
            <a class="btn btn-primary" href="{{ route('contact') }}">Request Consultation</a>
        </div>
        <div class="video-filter-row">
            <a class="{{ $activeCategory ? '' : 'is-active' }}" href="{{ route('videos.index') }}">All</a>
            @foreach($categories as $category)
                <a class="{{ $activeCategory === $category ? 'is-active' : '' }}" href="{{ route('videos.index',['category'=>$category]) }}">{{ $category }}</a>
            @endforeach
        </div>
        <div class="video-grid" style="margin-top:26px">
            @forelse($videos as $video)
                @include('partials.video-card', ['video' => $video])
            @empty
                <div class="empty-state">
                    <h2>No published videos yet.</h2>
                    <p class="section-sub">The video library is ready. Published Humelix videos will appear here after admin review.</p>
                    <a href="{{ route('contact') }}" class="btn btn-primary" style="margin-top:18px">Contact Humelix</a>
                </div>
            @endforelse
        </div>
        <div style="margin-top:28px">{{ $videos->links() }}</div>
    </div>
</section>
@include('partials.public-cta')
@endsection
