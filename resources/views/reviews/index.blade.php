@extends('layouts.app')
@section('title','Reviews — HUMELIX SYSTEMS')
@section('content')
@include('components.page-hero',['eyebrow'=>'Reviews','title'=>'Client feedback built on completed work.','subtitle'=>'Approved reviews from clients across our service environments.'])
<section class="section"><div class="container"><div class="grid grid-3">@forelse($reviews as $review)<blockquote class="card" style="margin:0" data-animate="fade-up" data-delay="{{ ($loop->index % 3) * 70 }}"><div class="stars" aria-label="5 out of 5 stars">★★★★★</div><p style="margin-top:16px">“{{ $review->comment }}”</p><footer><strong>{{ $review->client_name }}</strong><div class="meta">{{ $review->client_role }} · {{ $review->location }}</div></footer></blockquote>@empty<div class="empty-state"><h2>Approved client reviews will appear here.</h2><p class="section-sub">Ask our team for references relevant to your project type.</p></div>@endforelse</div><div style="margin-top:28px">{{ $reviews->links() }}</div></div></section>
@include('partials.public-cta')
@endsection
