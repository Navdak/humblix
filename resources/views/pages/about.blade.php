@extends('layouts.app')
@section('title','About - HUMELIX LIMITED')
@section('meta_description','About HUMELIX LIMITED, a global engineering services company for HVAC, solar, electrical, maintenance, vendor equipment and home appliance installation.')
@section('content')
@include('components.page-hero',[
    'eyebrow'=>'About Humelix',
    'title'=>'Global engineering services founded in 2018.',
    'subtitle'=>'HUMELIX LIMITED delivers HVAC, solar, electrical, maintenance, equipment/vendor support and home appliance installation for residential, commercial and industrial clients.'
])
<section class="section">
    <div class="container why-layout">
        <div data-animate="slide-right">
            <span class="eyebrow">Who We Are</span>
            <h2 class="section-title">Precision power. Flawless comfort. Practical delivery.</h2>
            <p class="section-sub">Humelix is powered by more than 500 staff across global operations, bringing together engineering, installation, maintenance, safety, support and regional service teams.</p>
            <p>The company is structured to support projects across multiple locations and service divisions while keeping safety, communication and accountability central to delivery.</p>
            <div style="margin-top:24px"><a href="{{ route('contact') }}" class="btn btn-primary">Request Consultation</a></div>
        </div>
        <div class="grid grid-2">
            <div class="card" data-animate="fade-up"><h3>HVAC</h3><p>Comfort, ventilation and climate-control support for homes, offices, towers and industrial spaces.</p></div>
            <div class="card" data-animate="fade-up" data-delay="70"><h3>Solar & Power</h3><p>Solar, inverter, battery and energy-support pathways for cleaner, more resilient power planning.</p></div>
            <div class="card" data-animate="fade-up"><h3>Electrical & Maintenance</h3><p>Electrical installation, inspection, servicing and preventive maintenance with safety-conscious planning.</p></div>
            <div class="card" data-animate="fade-up" data-delay="70"><h3>Equipment Support</h3><p>Vendor and equipment support foundations for approved engineering products, accessories and parts.</p></div>
        </div>
    </div>
</section>
<section class="section" style="padding-top:0">
    <div class="container">
        <div class="grid grid-3">
            <article class="card" data-animate="fade-up"><span class="eyebrow">Safety Culture</span><h2>Safety before every installation.</h2><p>Humelix work should protect people, buildings and property through controlled procedures, supervision, PPE discipline and professional site conduct.</p><a class="text-link" href="{{ route('safety') }}">Read More <span aria-hidden="true">&rarr;</span></a></article>
            <article class="card" data-animate="fade-up" data-delay="70"><span class="eyebrow">Global Positioning</span><h2>Built for regional growth.</h2><p>Backed by more than 500 staff globally, Humelix continues to expand its engineering, technical, support and regional operations.</p><a class="text-link" href="{{ route('branches.index') }}">Read More <span aria-hidden="true">&rarr;</span></a></article>
            <article class="card" data-animate="fade-up" data-delay="140"><span class="eyebrow">Client Pathways</span><h2>Consultation-first experience.</h2><p>Every main page points visitors toward consultation, chat, WhatsApp or direct contact for practical next steps.</p><a class="text-link" href="{{ route('contact') }}">Contact Humelix <span aria-hidden="true">&rarr;</span></a></article>
        </div>
    </div>
</section>
@include('partials.public-cta')
@endsection
