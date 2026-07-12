@extends('layouts.app')
@section('title','Contact & Quote - HUMELIX SYSTEMS')
@section('meta_description','Request a Humelix Systems consultation for HVAC, solar, electrical, maintenance, vendor or home appliance installation services.')
@section('content')
@include('components.page-hero',[
    'eyebrow' => 'Contact Humelix',
    'title' => 'Request a service consultation.',
    'subtitle' => 'Tell us what you need, where the project is located and how you prefer to be contacted. We will generate a reference number for your request.'
])

<section class="section enquiry-section">
    <div class="container enquiry-layout">
        <div class="enquiry-card" data-animate="fade-up">
            <div class="section-head section-head-row enquiry-form-head">
                <div>
                    <span class="eyebrow">Project Enquiry</span>
                    <h2 class="section-title">Share your project details.</h2>
                </div>
                <span class="badge">Secure form</span>
            </div>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                    @if(session('reference_number'))
                        <br><strong>Reference:</strong> {{ session('reference_number') }}
                    @endif
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-error">
                    <strong>Please review the highlighted fields.</strong>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('contact.store') }}" enctype="multipart/form-data" class="form-grid enquiry-form">
                @csrf
                <div class="form-field">
                    <label for="full_name">Full Name *</label>
                    <input id="full_name" name="full_name" autocomplete="name" required placeholder="Your full name" value="{{ old('full_name') }}">
                </div>
                <div class="form-field">
                    <label for="company_name">Company Name</label>
                    <input id="company_name" name="company_name" autocomplete="organization" placeholder="Company or organisation" value="{{ old('company_name') }}">
                </div>
                <div class="form-field">
                    <label for="country">Country *</label>
                    <input id="country" name="country" required placeholder="Nigeria" value="{{ old('country') }}">
                </div>
                <div class="form-field">
                    <label for="state_city">State / City *</label>
                    <input id="state_city" name="state_city" required placeholder="Lagos, Abuja, Port Harcourt..." value="{{ old('state_city') }}">
                </div>
                <div class="form-field">
                    <label for="email">Email Address *</label>
                    <input id="email" name="email" type="email" autocomplete="email" required placeholder="name@example.com" value="{{ old('email') }}">
                </div>
                <div class="form-field">
                    <label for="phone_whatsapp">Phone / WhatsApp *</label>
                    <input id="phone_whatsapp" name="phone_whatsapp" autocomplete="tel" required placeholder="+234..." value="{{ old('phone_whatsapp') }}">
                </div>
                <div class="form-field full">
                    <label for="project_location">Project Location *</label>
                    <input id="project_location" name="project_location" required placeholder="Exact project address or nearest landmark" value="{{ old('project_location') }}">
                </div>
                <div class="form-field">
                    <label for="type_of_work">Type of Work *</label>
                    <select id="type_of_work" name="type_of_work" required>
                        <option value="">Select a service division</option>
                        @foreach($typeOfWorkOptions as $option)
                            <option value="{{ $option }}" @selected(old('type_of_work', $prefilledTypeOfWork) === $option)>{{ $option }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-field">
                    <label for="building_type">Building Type *</label>
                    <select id="building_type" name="building_type" required>
                        <option value="">Select building type</option>
                        @foreach($buildingTypeOptions as $option)
                            <option value="{{ $option }}" @selected(old('building_type', request('building')) === $option)>{{ $option }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-field">
                    <label for="preferred_contact">Preferred Contact *</label>
                    <select id="preferred_contact" name="preferred_contact" required>
                        <option value="">How should we contact you?</option>
                        @foreach($preferredContactOptions as $option)
                            <option value="{{ $option }}" @selected(old('preferred_contact') === $option)>{{ $option }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-field">
                    <label for="urgency">Timeline</label>
                    <select id="urgency" name="urgency">
                        @foreach(['Today','This week','This month','Planning stage'] as $urgency)
                            <option value="{{ $urgency }}" @selected(old('urgency') === $urgency)>{{ $urgency }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-field full">
                    <label for="brief_description">Brief Description *</label>
                    <textarea id="brief_description" name="brief_description" rows="5" required placeholder="Describe the installation, fault, site condition, equipment need or consultation request.">{{ old('brief_description') }}</textarea>
                </div>
                <div class="form-field full">
                    <label for="uploaded_files">Upload Photos</label>
                    <input id="uploaded_files" type="file" name="uploaded_files[]" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" multiple>
                    <small class="field-help">Optional. Up to 5 images, 5MB each. JPG, PNG and WebP only.</small>
                </div>
                <div class="form-field full">
                    <button class="btn btn-primary" type="submit">Submit Request</button>
                </div>
            </form>
        </div>

        <aside class="enquiry-side" data-animate="slide-left">
            <div class="card">
                <span class="eyebrow">What happens next?</span>
                <h2>Reference number, review and response.</h2>
                <ol class="enquiry-steps">
                    <li><strong>Submit request</strong><span>Your enquiry is stored securely with a Humelix reference number.</span></li>
                    <li><strong>Technical review</strong><span>We review service type, location, urgency and uploaded site photos.</span></li>
                    <li><strong>Follow up</strong><span>A representative contacts you through your preferred channel.</span></li>
                </ol>
            </div>
            <div class="card">
                <span class="eyebrow">Need a quicker path?</span>
                <h2>Chat with an engineer.</h2>
                <p>Open the Humelix assistant for a lighter enquiry if you only need a quick callback.</p>
                <button class="btn btn-primary btn-block" type="button" data-chat-open>Chat with Engineer</button>
            </div>
        </aside>
    </div>
</section>
@endsection
