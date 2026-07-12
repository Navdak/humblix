@extends('layouts.app')
@section('title','Industries Served - HUMELIX SYSTEMS')
@section('meta_description','Explore the industries Humelix Systems supports with HVAC, solar, electrical, maintenance, vendor and home appliance services.')
@section('content')
@include('components.page-hero',['eyebrow'=>'Industries','title'=>'Industries Served','subtitle'=>'Humelix supports residential, commercial, industrial, institutional and public-sector environments with practical engineering services.'])
<section class="section phase6-section">
    <div class="container">
        <div class="section-head section-head-row">
            <div><span class="eyebrow">Operating Environments</span><h2 class="section-title">Built for real buildings, teams and operating demands.</h2></div>
            <a href="{{ route('contact') }}?service={{ urlencode('consultation') }}" class="btn btn-primary">Request Consultation</a>
        </div>
        <div class="phase6-card-grid">
            @foreach($industries as $industry)
                <article class="phase6-card" data-animate="fade-up" data-delay="{{ ($loop->index % 4) * 50 }}">
                    <div class="image-frame phase6-card-image"><img loading="lazy" src="{{ asset($industry['image']) }}" alt="{{ $industry['title'] }}"></div>
                    <span class="phase6-code">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                    <h3>{{ $industry['title'] }}</h3>
                    <p>{{ $industry['description'] }}</p>
                    <div class="phase6-chip-row">@foreach($services as $service)<span>{{ $service }}</span>@endforeach</div>
                    <a class="card-link" href="{{ route('industries.show', $industry['slug']) }}">View industry <span>&rarr;</span></a>
                </article>
            @endforeach
        </div>
    </div>
</section>
@include('partials.public-cta')
@endsection
