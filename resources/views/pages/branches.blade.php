@extends('layouts.app')
@section('title','Branches - HUMELIX SYSTEMS')
@section('meta_description','Find published HUMELIX SYSTEMS branch contact points and regional engineering service coverage.')
@section('content')
@include('components.page-hero',['eyebrow'=>'Branches','title'=>'Regional engineering support, prepared for branch-led service.','subtitle'=>'Published Humelix branch information appears here as country and city operations come online.'])
<section class="section phase6-section">
    <div class="container">
        <div class="section-head section-head-row">
            <div><span class="eyebrow">Service Coverage</span><h2 class="section-title">Country and city contact foundations.</h2><p class="section-sub">Use this page to route enquiries to published branch teams. If a branch is not listed yet, the central Humelix team can still receive and triage your request.</p></div>
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
                    <div class="phase6-chip-row">
                        @if($branch->phone)<span>{{ $branch->phone }}</span>@endif
                        @if($branch->email)<span>{{ $branch->email }}</span>@endif
                        @if($branch->manager_name)<span>{{ $branch->manager_name }}</span>@endif
                    </div>
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
