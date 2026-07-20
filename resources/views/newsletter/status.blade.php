@extends('layouts.app')
@section('title', $title.' - HUMELIX LIMITED')
@section('meta_description', $message)
@section('content')
@include('components.page-hero',[
    'eyebrow'=>'Newsletter',
    'title'=>$title,
    'subtitle'=>$message,
])
<section class="section">
    <div class="container">
        <div class="empty-state">
            <h2>{{ $title }}</h2>
            <p class="section-sub">{{ $message }}</p>
            <a class="btn btn-primary" href="{{ $actionUrl }}" style="margin-top:18px">{{ $actionLabel }}</a>
        </div>
    </div>
</section>
@endsection
