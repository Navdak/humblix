<button class="chat-launcher" type="button" aria-label="Open HUMELIX LIMITED assistant" aria-expanded="false" aria-controls="chat-panel" data-chat-toggle>
    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M21 11.5a8.4 8.4 0 0 1-9 8.4 9.6 9.6 0 0 1-3.8-.8L3 21l1.7-4.5A8.5 8.5 0 1 1 21 11.5Z"/><path d="M8 12h.01M12 12h.01M16 12h.01"/></svg>
    <span>Chat with us</span>
</button>
<aside id="chat-panel" class="chat-panel" aria-label="HUMELIX LIMITED assistant" hidden data-chat-panel>
    <div class="chat-head"><div class="chat-identity"><span class="brand-mark" aria-hidden="true"><img loading="lazy" decoding="async" width="84" height="84" src="{{ asset('images/brand/humelix-logo-mark.png') }}" alt=""></span><div><strong>HUMELIX LIMITED Assistant</strong><span><i></i> Online</span></div></div><button type="button" aria-label="Close assistant" data-chat-close>×</button></div>
    <div class="chat-body">
        <div class="chat-message">Hello, welcome to <strong>Humelix Limited.</strong><br>What service do you need today?</div>
        <div class="chat-status" role="status" hidden data-chat-status></div>
        <div class="quick-buttons" aria-label="Choose a service">
            @foreach(['HVAC','Solar','Electrical','Maintenance','Vendor','Home Appliance','Speak to Admin','Request Service'] as $option)<button type="button" data-service-option="{{ $option }}">{{ $option }}</button>@endforeach
        </div>
        <form action="{{ route('chat.enquiry') }}" method="POST" class="chat-form" data-chat-form>
            @csrf
            <div class="form-grid">
                <div class="form-field"><label for="chat-name">Name</label><input id="chat-name" name="name" autocomplete="name" required></div>
                <div class="form-field"><label for="chat-phone">Phone / WhatsApp</label><input id="chat-phone" name="phone" autocomplete="tel" required></div>
                <div class="form-field"><label for="chat-email">Email</label><input id="chat-email" name="email" type="email" autocomplete="email"></div>
                <div class="form-field"><label for="chat-location">Location</label><input id="chat-location" name="location" placeholder="City or project area"></div>
                <div class="form-field full"><label for="chat-service">Service Needed</label><input id="chat-service" name="service_needed" required data-chat-service></div>
                <div class="form-field full"><label for="chat-building">Building Type</label><input id="chat-building" name="building_type" placeholder="Home, office, factory..."></div>
                <div class="form-field full"><label for="chat-message">Project details</label><textarea id="chat-message" name="message" rows="3"></textarea></div>
                <div class="spam-trap" aria-hidden="true"><label>Website <input name="website" tabindex="-1" autocomplete="off"></label></div>
            </div>
            <input type="hidden" name="form_started_at" value="{{ time() }}">
            <input type="hidden" name="urgency" value="This week"><button class="btn btn-primary btn-block" type="submit">Request Service</button>
        </form>
    </div>
</aside>
