@extends('layouts.app')
@section('title',$member->name.' - Team - HUMELIX SYSTEMS')
@section('meta_description',\Illuminate\Support\Str::limit(strip_tags($member->bio ?: $member->role.' at HUMELIX SYSTEMS.'), 155))
@section('content')
@include('components.page-hero',[
    'eyebrow'=>'Team Profile',
    'title'=>$member->name,
    'subtitle'=>trim(($member->role ?: 'Humelix team member').' · '.($member->region ?: 'Global Operations'))
])

<section class="section">
    <div class="container service-detail-layout">
        <article class="service-main">
            <div class="service-block" data-animate="fade-up">
                <span class="eyebrow">Professional Biography</span>
                <h2>{{ $member->role ?: 'Humelix Team Member' }}</h2>
                <p>{{ $member->bio ?: 'This Humelix profile is available for verified team information, department focus and client-facing biography updates. The admin team can replace this temporary summary when final staff details are approved.' }}</p>
                <ul class="content-summary-list">
                    <li><strong>Service / department focus:</strong> {{ $member->role ?: 'Engineering and support operations' }}</li>
                    <li><strong>Region:</strong> {{ $member->region ?: 'Global Operations' }}</li>
                    <li><strong>Experience summary:</strong> {{ $member->experience ?: 'To be updated by the Humelix admin team' }}</li>
                </ul>
            </div>

            <div class="service-block" data-animate="fade-up">
                <span class="eyebrow">Leadership & Delivery Focus</span>
                <h2>Safety, quality and accountability in project delivery.</h2>
                <p>Humelix team profiles are kept intentionally professional until verified personal details are supplied. This avoids fake certifications, unverifiable awards or unsupported client claims while still giving visitors a clear view of each person’s role.</p>
                <p>Team content can be updated from the admin Team module as real biographies, photos and contact details are confirmed.</p>
            </div>
        </article>

        <aside class="service-sidebar" data-animate="slide-left">
            <div class="service-sticky-card">
                <div class="image-frame" style="aspect-ratio:4/5;margin-bottom:20px">
                    <img src="{{ \App\Support\UchContent::imageUrl($member->photo_path, \App\Support\UchContent::teamImage($member->role)) }}" alt="{{ $member->name }}">
                </div>
                <h2>{{ $member->name }}</h2>
                <p>{{ $member->role ?: 'Humelix Team' }}</p>
                <a class="btn btn-primary btn-block" href="{{ route('contact') }}?service={{ urlencode('Team enquiry: '.$member->name) }}">Contact Humelix</a>
                <a class="btn btn-white btn-block" href="{{ route('team.index') }}">Back to Team</a>
                @if($member->email)
                    <a class="btn btn-outline btn-block" href="mailto:{{ $member->email }}">Email Profile Contact</a>
                @endif
            </div>
        </aside>
    </div>
</section>

<section class="section related-service-section">
    <div class="container">
        <div class="section-head section-head-row">
            <div>
                <span class="eyebrow">Related Team</span>
                <h2 class="section-title">More Humelix profiles.</h2>
            </div>
            <a class="btn btn-outline" href="{{ route('team.index') }}">View Team</a>
        </div>
        <div class="grid grid-4">
            @foreach($relatedMembers as $related)
                <a class="project-card" href="{{ route('team.show', $related) }}" data-animate="fade-up">
                    <div class="image-frame" style="aspect-ratio:4/5">
                        <img loading="lazy" src="{{ \App\Support\UchContent::imageUrl($related->photo_path, \App\Support\UchContent::teamImage($related->role)) }}" alt="{{ $related->name }}">
                    </div>
                    <div class="project-body">
                        <h3>{{ $related->name }}</h3>
                        <p>{{ $related->role }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endsection
