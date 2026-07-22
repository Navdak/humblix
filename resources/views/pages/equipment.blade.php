@extends('layouts.app')
@section('title','Equipment & Vendor Supply - HUMELIX LIMITED')
@section('meta_description','Request HVAC, solar, electrical, maintenance and home installation equipment from HUMELIX LIMITED vendor support.')
@section('content')
@php
    $vendorQuoteUrl = route('contact').'?type_of_work=Vendor&service='.urlencode('Vendor / Equipment Quote');
@endphp
@include('components.page-hero',['eyebrow'=>'Equipment / Vendor','title'=>'Equipment supply and vendor quote foundation.','subtitle'=>'Humelix supports project teams with equipment sourcing, technical specification review and request-based vendor coordination.'])
<section class="section phase6-section">
    <div class="container">
        <div class="section-head section-head-row">
            <div><span class="eyebrow">Categories</span><h2 class="section-title">Request-based supply, not online checkout.</h2><p class="section-sub">This foundation supports vendor enquiries for real projects. Pricing, stock checks and delivery details are confirmed by the Humelix team.</p></div>
            <a href="{{ $vendorQuoteUrl }}" class="btn btn-primary">Request Equipment Quote</a>
        </div>
        <div class="phase6-category-grid">
            @foreach($categories as $category)
                <a class="phase6-category-card" href="{{ route('contact') }}?type_of_work=Vendor&service={{ urlencode('Equipment: '.$category) }}" data-animate="fade-up" data-delay="{{ ($loop->index % 4) * 45 }}">
                    <div class="image-frame phase6-category-image"><img loading="lazy" decoding="async" width="800" height="450" src="{{ asset($categoryImages[$category] ?? \App\Support\UchContent::equipmentImage($category)) }}" alt="{{ $category }}"></div>
                    <span class="phase6-code">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                    <strong>{{ $category }}</strong>
                    <small>Request specification, availability and sourcing support.</small>
                </a>
            @endforeach
        </div>
    </div>
</section>
<section class="section">
    <div class="container">
        <div class="section-head section-head-row">
            <div><span class="eyebrow">Catalogue Foundation</span><h2 class="section-title">Published equipment items.</h2></div>
            <a href="{{ route('contact') }}?type_of_work=Vendor&service={{ urlencode('Vendor') }}" class="btn btn-outline">Ask Vendor Team</a>
        </div>
        <div class="grid grid-3">
            @forelse($items as $item)
                <article class="project-card" data-animate="fade-up" data-delay="{{ ($loop->index % 3) * 60 }}">
                    <div class="image-frame"><img loading="lazy" decoding="async" width="800" height="500" src="{{ \App\Support\UchContent::imageUrl($item->image_path, \App\Support\UchContent::equipmentImage($item->category)) }}" alt="{{ $item->name }}"></div>
                    <div class="project-body">
                        <span class="badge">{{ str_replace('_',' ', $item->availability_status) }}</span>
                        <h3 style="margin-top:12px">{{ $item->name }}</h3>
                        <p>{{ $item->category }}</p>
                        @if($item->short_description)<p>{{ $item->short_description }}</p>@endif
                        <a class="card-link" href="{{ route('contact') }}?type_of_work=Vendor&service={{ urlencode('Equipment quote: '.$item->name) }}">Request quote <span>&rarr;</span></a>
                    </div>
                </article>
            @empty
                <div class="empty-state">
                    <h2>No equipment items are published yet.</h2>
                    <p class="section-sub">The vendor catalogue foundation is ready. Publish verified items from admin when product details are approved.</p>
                    <a href="{{ $vendorQuoteUrl }}" class="btn btn-primary" style="margin-top:18px">Request Vendor Support</a>
                </div>
            @endforelse
        </div>
    </div>
</section>
@include('partials.video-section', [
    'videos' => $equipmentVideos,
    'eyebrow' => 'Vendor / Equipment Videos',
    'title' => 'Product demos and equipment showcases',
    'subtitle' => 'Published vendor, product and equipment videos appear here when available.',
])
@include('partials.public-cta')
@endsection
