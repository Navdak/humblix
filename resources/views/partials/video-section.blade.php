@props(['videos', 'eyebrow' => 'Videos', 'title' => 'Field Work & Product Highlights', 'subtitle' => null, 'cta' => true])
@if($videos->isNotEmpty())
<section class="section video-section">
    <div class="container">
        <div class="section-head section-head-row">
            <div>
                <span class="eyebrow">{{ $eyebrow }}</span>
                <h2 class="section-title">{{ $title }}</h2>
                @if($subtitle)<p class="section-sub">{{ $subtitle }}</p>@endif
            </div>
            @if($cta)<a class="btn btn-outline" href="{{ route('videos.index') }}">View Video Library</a>@endif
        </div>
        <div class="video-grid">
            @foreach($videos as $video)
                @include('partials.video-card', ['video' => $video])
            @endforeach
        </div>
    </div>
</section>
@endif
