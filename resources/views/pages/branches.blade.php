@extends('layouts.app')
@section('title','Branches - HUMELIX LIMITED')
@section('meta_description','Find published HUMELIX LIMITED branch contact points and regional engineering service coverage.')
@section('content')
@include('components.page-hero',['eyebrow'=>'Branches','title'=>'Regional engineering support, prepared for branch-led service.','subtitle'=>'Our regional structure supports site response, project coordination and client service across multiple operating locations as Humelix continues to expand.'])
<section class="section phase6-section">
    <div class="container">
        <div class="section-head section-head-row">
            <div><span class="eyebrow">Service Coverage</span><h2 class="section-title">Country and city contact foundations.</h2><p class="section-sub">Use this page to route enquiries to published branch teams. Backed by more than 500 staff across global operations, Humelix can still receive and triage requests through the central team while new regional details are verified.</p></div>
            <a href="{{ route('contact') }}" class="btn btn-primary">Contact Humelix</a>
        </div>
        <div class="phase6-card-grid">
            @forelse($branches as $branch)
                <article class="phase6-card" data-animate="fade-up" data-delay="{{ ($loop->index % 4) * 50 }}">
                    <span class="phase6-code">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                    <h3>{{ $branch->name }}</h3>
                    <p>{{ trim(($branch->state_city ? $branch->state_city.', ' : '').$branch->country) }}</p>
                    @if($branch->address)<p class="meta">{{ $branch->address }}</p>@endif
                    @if($branch->service_coverage)<p>{{ $branch->service_coverage }}</p>@endif
                    <a class="card-link" href="{{ route('contact') }}?location={{ urlencode($branch->state_city ?: $branch->country) }}">Request local support <span>&rarr;</span></a>
                </article>
            @empty
                <div class="empty-state">
                    <h2>No published branches yet.</h2>
                    <p class="section-sub">The branch module is ready. Publish real branch records from admin when verified office/contact details are available.</p>
                    <a href="{{ route('contact') }}" class="btn btn-primary" style="margin-top:18px">Contact Central Team</a>
                </div>
            @endforelse
        </div>
    </div>
</section>
@include('partials.video-section', [
    'videos' => $branchVideos,
    'eyebrow' => 'Branch Videos',
    'title' => 'Branch and team operations',
    'subtitle' => 'Published branch-related videos appear here when available.',
])
@include('partials.public-cta')
@endsection
