@extends('layouts.app')
@section('title', $content['title'].' - HUMELIX LIMITED')
@section('meta_description', $content['intro'])
@section('seo_key', $page)
@section('content')
<x-page-hero :eyebrow="$content['eyebrow']" :title="$content['title']" :subtitle="$content['intro']" />
<section class="section">
    <div class="container narrow legal-content">
        @foreach($content['sections'] as [$title, $body])
            <article class="card" data-animate="fade-up">
                <h2>{{ $title }}</h2>
                <p>{{ $body }}</p>
            </article>
        @endforeach
        <div class="cta-panel" data-animate="fade-up">
            <div>
                <span class="eyebrow">Need clarification?</span>
                <h2>Contact HUMELIX LIMITED</h2>
                <p>For privacy, terms, cookie or accessibility questions, contact the Humelix team through the enquiry page.</p>
            </div>
            <a class="btn btn-primary" href="{{ route('contact') }}">Contact Humelix</a>
        </div>
        <p class="meta" style="margin-top:18px">This page provides general website information and is not legal advice. It should be reviewed by qualified counsel before use as a jurisdiction-specific legal policy.</p>
    </div>
</section>
@endsection
