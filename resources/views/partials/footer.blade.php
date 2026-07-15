<footer class="footer" data-animate="fade-in">
    <div class="container footer-grid">
        <div class="footer-brand">
            <a href="{{ route('home') }}" class="brand"><span class="brand-mark">H</span><span class="brand-text"><strong>HUMELIX</strong><small>SYSTEMS</small></span></a>
            <p>HVAC, solar, electrical, maintenance, equipment/vendor and home installation solutions delivered by an expanding global team of 500+ staff with disciplined workmanship and dependable aftercare.</p>
            <div class="footer-contact"><a href="tel:{{ $globalSettings['phone_primary'] ?? '+2349001234567' }}">{{ $globalSettings['phone_primary'] ?? '+234 900 123 4567' }}</a><a href="mailto:{{ $globalSettings['company_email'] ?? 'info@humelix.com' }}">{{ $globalSettings['company_email'] ?? 'info@humelix.com' }}</a></div>
        </div>
        <div><h4>Company</h4><a href="{{ route('about') }}">About Humelix</a><a href="{{ route('team.index') }}">Team</a><a href="{{ route('branches.index') }}">Branches</a><a href="{{ route('careers.index') }}">Careers</a></div>
        <div><h4>Platform</h4><a href="{{ route('services.index') }}">Services</a><a href="{{ route('industries.index') }}">Industries</a><a href="{{ route('projects.index') }}">Projects</a><a href="{{ route('equipment.index') }}">Equipment</a><a href="{{ route('safety') }}">Safety</a></div>
        <div><h4>Resources</h4><a href="{{ route('articles.index') }}">Engineering Guides</a><a href="{{ route('videos.index') }}">Videos</a><a href="{{ route('reviews.index') }}">Reviews</a><a href="{{ route('contact') }}">Request Consultation</a><a href="mailto:{{ $globalSettings['support_email'] ?? 'support@humelix.com' }}">Email Support</a></div>
        <div><h4>Legal</h4><a href="{{ route('legal.show', 'privacy-policy') }}">Privacy Policy</a><a href="{{ route('legal.show', 'terms') }}">Terms</a><a href="{{ route('legal.show', 'cookie-policy') }}">Cookie Policy</a><a href="{{ route('legal.show', 'accessibility') }}">Accessibility</a></div>
        <div><h4>Contact</h4><a href="{{ route('contact') }}">Contact Humelix</a><a href="{{ route('contact') }}">Request Service</a><button type="button" data-chat-open>Chat with Engineer</button><a href="https://wa.me/{{ preg_replace('/\D+/', '', $globalSettings['whatsapp_number'] ?? '+2349001234567') }}" target="_blank" rel="noopener">WhatsApp Us</a></div>
    </div>
    <div class="container footer-bottom"><span>{{ $globalSettings['footer_copyright'] ?? '© '.date('Y').' HUMELIX SYSTEMS. All rights reserved.' }}</span><span><a href="{{ route('legal.show', 'privacy-policy') }}">Privacy Policy</a> · <a href="{{ route('legal.show', 'terms') }}">Terms</a> · <a href="{{ route('legal.show', 'cookie-policy') }}">Cookie Policy</a> · <a href="{{ route('legal.show', 'accessibility') }}">Accessibility</a></span></div>
</footer>
