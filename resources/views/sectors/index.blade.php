@extends('layouts.app')
@section('title','Industries — HUMELIX SYSTEMS')
@section('content')
@include('components.page-hero',['eyebrow'=>'Industries','title'=>'This page has moved to Industries.','subtitle'=>'The Humelix public architecture now uses Industries. Existing sector detail links remain available for compatibility.'])
<section class="section"><div class="container"><div class="empty-state"><h2>Continue to the Industries page.</h2><p class="section-sub">Use the new Humelix industries route for the current page foundation.</p><a href="{{ route('industries.index') }}" class="btn btn-primary" style="margin-top:18px">Open Industries</a></div></div></section>
@endsection
