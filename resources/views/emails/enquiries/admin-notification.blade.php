<p>A new HUMELIX SYSTEMS enquiry has been submitted.</p>

<p><strong>Reference number:</strong> {{ $enquiry->reference_number }}</p>
<p><strong>Client:</strong> {{ $enquiry->full_name }}</p>
<p><strong>Company:</strong> {{ $enquiry->company_name ?: 'Not provided' }}</p>
<p><strong>Type of work:</strong> {{ $enquiry->display_type_of_work }}</p>
<p><strong>Location:</strong> {{ $enquiry->country ?: 'Country not set' }}{{ $enquiry->state_city ? ' - '.$enquiry->state_city : '' }}</p>
<p><strong>Project location:</strong> {{ $enquiry->display_location }}</p>
<p><strong>Preferred contact:</strong> {{ $enquiry->preferred_contact ?: 'Not specified' }}</p>
<p><strong>Phone / WhatsApp:</strong> {{ $enquiry->phone_whatsapp }}</p>
<p><strong>Email:</strong> {{ $enquiry->email ?: 'Not provided' }}</p>

@if(Route::has('admin.enquiries.show'))
    <p><a href="{{ route('admin.enquiries.show', $enquiry) }}">Open enquiry in admin</a></p>
@endif
