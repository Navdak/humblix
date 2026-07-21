@extends('layouts.app')
@section('title','Reviews — HUMELIX LIMITED')
@section('content')
@include('components.page-hero',['eyebrow'=>'Reviews','title'=>'Client feedback built on completed work.','subtitle'=>'Approved reviews from clients across our service environments.'])

<section class="section">
    <div class="container">
        <div class="review-action-panel" data-animate="fade-up">
            <div>
                <span class="eyebrow">Client Feedback</span>
                <h2>Share your Humelix experience.</h2>
                <p class="section-sub">You can review HUMELIX LIMITED on Google or submit feedback here for admin moderation before it appears publicly.</p>
            </div>
            <div class="review-action-buttons">
                @if(!empty($globalSettings['google_review_url']))
                    <a class="btn btn-primary" href="{{ $globalSettings['google_review_url'] }}" target="_blank" rel="noopener">Review us on Google</a>
                @else
                    <span class="btn btn-white" aria-disabled="true">Google review link coming soon</span>
                @endif
                <a class="btn btn-white" href="#submit-review">Submit Website Review</a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success" style="margin-top:22px">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-error" style="margin-top:22px">
                <strong>Please review the highlighted fields.</strong>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</section>

<section class="section" style="padding-top:0">
    <div class="container">
        <div class="grid grid-3">
            @forelse($reviews as $review)
                <blockquote class="card" style="margin:0" data-animate="fade-up" data-delay="{{ ($loop->index % 3) * 70 }}">
                    <div class="stars" aria-label="{{ $review->rating }} out of 5 stars">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</div>
                    <p style="margin-top:16px">“{{ $review->comment }}”</p>
                    @if($review->admin_response)
                        <p class="review-response"><strong>Humelix response:</strong> {{ $review->admin_response }}</p>
                    @endif
                    <footer>
                        <strong>{{ $review->client_name }}</strong>
                        <div class="meta">{{ $review->client_role }}{{ $review->client_role && $review->location ? ' · ' : '' }}{{ $review->location }}</div>
                    </footer>
                </blockquote>
            @empty
                <div class="empty-state"><h2>Approved client reviews will appear here.</h2><p class="section-sub">Ask our team for references relevant to your project type.</p></div>
            @endforelse
        </div>
        <div style="margin-top:28px">{{ $reviews->links() }}</div>
    </div>
</section>

<section id="submit-review" class="section" style="padding-top:0">
    <div class="container">
        <form class="card review-submit-form" method="POST" action="{{ route('reviews.store') }}" data-animate="fade-up">
            @csrf
            <div class="section-head left" style="margin-bottom:22px">
                <span class="eyebrow">Website Review</span>
                <h2 class="section-title">Submit feedback for approval.</h2>
                <p class="section-sub">Your review will be checked by permitted HUMELIX admins before it appears on this page.</p>
            </div>
            <div class="form-grid">
                <div class="form-field"><label for="client_name">Name *</label><input id="client_name" name="client_name" value="{{ old('client_name') }}" required></div>
                <div class="form-field"><label for="client_role">Role</label><input id="client_role" name="client_role" value="{{ old('client_role') }}" placeholder="Facility Manager, Homeowner..."></div>
                <div class="form-field"><label for="company">Company</label><input id="company" name="company" value="{{ old('company') }}"></div>
                <div class="form-field"><label for="location">Location</label><input id="location" name="location" value="{{ old('location') }}" placeholder="Lagos, Abuja..."></div>
                <div class="form-field"><label for="project_category">Service Type</label><input id="project_category" name="project_category" value="{{ old('project_category') }}" placeholder="HVAC, Solar, Maintenance..."></div>
                <div class="form-field">
                    <label for="rating">Rating *</label>
                    <select id="rating" name="rating" required>
                        @foreach([5,4,3,2,1] as $rating)
                            <option value="{{ $rating }}" @selected((int) old('rating', 5) === $rating)>{{ $rating }} Star{{ $rating > 1 ? 's' : '' }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-field full"><label for="comment">Review *</label><textarea id="comment" name="comment" rows="5" required minlength="20" maxlength="1500" placeholder="Tell us what went well, what was delivered, and your experience with the Humelix team.">{{ old('comment') }}</textarea></div>
                <div class="spam-trap" aria-hidden="true"><label>Website <input name="website" tabindex="-1" autocomplete="off"></label></div>
                <input type="hidden" name="form_started_at" value="{{ time() }}">
                <label class="newsletter-consent full">
                    <input type="checkbox" name="review_consent" value="1" required>
                    <span>I confirm this is genuine feedback and agree that HUMELIX LIMITED may review and publish it on this website.</span>
                </label>
                <div class="form-field full"><button class="btn btn-primary" type="submit">Submit Review</button></div>
            </div>
        </form>
    </div>
</section>

@include('partials.public-cta')
@endsection
