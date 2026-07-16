@props(['video'])
@php
    $playbackUrl = $video->playbackUrl();
    $thumbnailUrl = $video->thumbnailUrl() ?: asset(\App\Support\UchContent::videoImage($video->category));
    $relatedLabel = $video->project?->title ?: ($video->branch?->name ?: ($video->equipment?->name ?: $video->related_service));
@endphp
<article class="video-card" data-animate="fade-up">
    <button
        type="button"
        class="video-thumb"
        data-video-open
        data-video-title="{{ $video->title }}"
        data-video-caption="{{ $video->caption }}"
        data-video-src="{{ $playbackUrl }}"
        data-video-kind="{{ $video->playbackKind() }}"
        data-video-poster="{{ $thumbnailUrl }}"
        aria-label="Play {{ $video->title }}"
    >
        <img loading="lazy" src="{{ $thumbnailUrl }}" alt="{{ $video->title }} thumbnail">
        <span class="video-play" aria-hidden="true">▶</span>
    </button>
    <div class="video-card-body">
        <span class="badge">{{ $video->categoryLabel() }}</span>
        <h3>{{ $video->title }}</h3>
        @if($video->caption)<p>{{ $video->caption }}</p>@endif
        @if($relatedLabel)<small>{{ $relatedLabel }}</small>@endif
    </div>
</article>
