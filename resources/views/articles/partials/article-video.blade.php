@if($article->hasArticleVideo())
    <aside class="article-video-card" data-animate="fade-up">
        <div class="article-video-copy">
            <span class="eyebrow">Article Video</span>
            <h2>{{ $article->video_title ?: 'Watch the supporting video' }}</h2>
            @if($article->video_caption)
                <p>{{ $article->video_caption }}</p>
            @else
                <p>This video supports the article with practical visual context.</p>
            @endif
        </div>
        <div class="article-video-frame">
            @if($article->articleVideoPlaybackKind() === 'video')
                <video src="{{ $article->video_embed_url }}" controls preload="metadata"></video>
            @else
                <iframe
                    src="{{ $article->video_embed_url }}"
                    title="{{ $article->video_title ?: $article->title }}"
                    loading="lazy"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                    allowfullscreen></iframe>
            @endif
        </div>
    </aside>
@endif
