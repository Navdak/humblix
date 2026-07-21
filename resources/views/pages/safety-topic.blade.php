@extends('layouts.app')
@section('title',$topic['title'].' - Safety Centre - Humelix Limited')
@section('meta_description',$topic['description'])
@section('content')
@php
    $topicImageUrl = $topicRecord?->imageUrl() ?? ($topic['image_url'] ?? \App\Support\UchContent::imageUrl($topic['image'] ?? null, \App\Support\UchContent::safetyImage($topic['title'])));
    [$contentBeforeVideo, $contentAfterVideo] = $topicRecord?->sanitizedContentSegmentsForVideo() ?? ['', ''];
@endphp
<section class="safety-topic-hero" style="--safety-hero-image:url('{{ $topicImageUrl }}')">
    <div class="container">
        <a class="back-link" href="{{ route('safety') }}">← Back to Safety Centre</a>
        <span class="eyebrow">Safety Topic</span>
        <h1>{{ $topic['title'] }}</h1>
        <p>{{ $topic['description'] }}</p>
    </div>
</section>

<section class="section">
    <div class="container safety-topic-layout">
        <article class="safety-topic-card prose" data-animate="fade-up">
            <div class="image-frame" style="margin-bottom:24px">
                <img src="{{ $topicImageUrl }}" alt="{{ $topic['title'] }}">
            </div>
            <span class="safety-seal">SAFE</span>
            <h2>What this means on site</h2>
            @if($topicRecord?->hasVideo() && $topicRecord->videoPlacement() === 'after_intro')
                @include('pages.partials.safety-topic-video', ['topicRecord' => $topicRecord])
            @endif
            @if($topicRecord)
                {!! $contentBeforeVideo !!}
                @if($topicRecord->hasVideo() && $topicRecord->videoPlacement() === 'middle')
                    @include('pages.partials.safety-topic-video', ['topicRecord' => $topicRecord])
                @endif
                {!! $contentAfterVideo !!}
                @if($topicRecord->hasVideo() && $topicRecord->videoPlacement() === 'end')
                    @include('pages.partials.safety-topic-video', ['topicRecord' => $topicRecord])
                @endif
            @else
                <p>{{ $topic['detail'] }}</p>
            @endif
            <ul class="content-summary-list">
                @foreach($topic['summary'] ?? [] as $item)
                    <li>{{ $item }}</li>
                @endforeach
            </ul>
            <p>Humelix uses this topic as part of a broader safety-first culture. It supports planning, communication, inspection, testing, commissioning and documented handover without claiming unsupported certifications or guarantees.</p>
            <p>We do not compromise safety on client sites. Work is expected to follow applicable procedures, safe access planning, PPE expectations and accountable supervision.</p>
            <div class="service-actions">
                <a class="btn btn-primary" href="{{ $topicRecord?->cta_url ?: route('contact').'?service='.urlencode('Maintenance') }}">{{ $topicRecord?->cta_label ?: 'Request Site Inspection' }}</a>
                <a class="btn btn-white" href="{{ route('safety') }}">View Safety Centre</a>
                <button class="btn btn-outline" type="button" data-chat-open>Talk to Humelix</button>
            </div>
        </article>

        <aside class="safety-topic-nav" data-animate="slide-left">
            <h2>Safety Framework</h2>
            <p>Explore the complete Humelix safety framework.</p>
            @foreach($modules as $module)
                <a class="{{ $module['slug'] === $topic['slug'] ? 'is-active' : '' }}" href="{{ route('safety.topic', $module['slug']) }}">{{ $module['title'] }}</a>
            @endforeach
        </aside>
    </div>
</section>
@endsection
