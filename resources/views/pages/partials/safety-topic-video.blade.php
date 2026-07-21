@if($topicRecord?->hasVideo())
    <aside class="article-video-card" data-animate="fade-up">
        <div class="article-video-copy">
            <span class="eyebrow">Safety Video</span>
            <h2>{{ $topicRecord->video_title ?: 'Watch the supporting safety video' }}</h2>
            @if($topicRecord->video_caption)
                <p>{{ $topicRecord->video_caption }}</p>
            @else
                <p>This video supports the safety topic with practical visual context.</p>
            @endif
        </div>
        <div class="article-video-frame {{ $topicRecord->videoPlaybackAspect() === 'short' ? 'is-short' : '' }}">
            @if($topicRecord->videoPlaybackKind() === 'video')
                <video src="{{ $topicRecord->video_embed_url }}" controls preload="metadata"></video>
            @else
                <iframe
                    src="{{ $topicRecord->video_embed_url }}"
                    title="{{ $topicRecord->video_title ?: $topicRecord->title }}"
                    loading="lazy"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                    allowfullscreen></iframe>
            @endif
        </div>
    </aside>
@endif
