<div class="newsletter-card" data-animate="fade-up">
    <div>
        <span class="eyebrow">Humelix Newsletter</span>
        <h2>Get new engineering resources by email.</h2>
        <p class="section-sub">Subscribe for HVAC, solar, electrical, safety, maintenance and vendor/equipment guides from HUMELIX LIMITED. No confirmation step required.</p>
    </div>
    <form class="newsletter-form" method="POST" action="{{ route('newsletter.subscribe') }}">
        @csrf
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @error('email')
            <div class="alert alert-error">{{ $message }}</div>
        @enderror
        @error('newsletter_consent')
            <div class="alert alert-error">{{ $message }}</div>
        @enderror
        <div class="newsletter-fields">
            <input type="text" name="name" value="{{ old('name') }}" placeholder="Your name (optional)" autocomplete="name">
            <input type="email" name="email" value="{{ old('email') }}" placeholder="Email address" autocomplete="email" required>
            <button class="btn btn-primary" type="submit">Subscribe</button>
        </div>
        <label class="newsletter-consent">
            <input type="checkbox" name="newsletter_consent" value="1" required>
            <span>I agree to receive HUMELIX LIMITED resource updates. I can unsubscribe anytime.</span>
        </label>
    </form>
</div>
