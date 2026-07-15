<p>Hello {{ $enquiry->full_name }},</p>

<p>Thank you. Your request has been received by HUMELIX LIMITED.</p>

<p><strong>Reference number:</strong> {{ $enquiry->reference_number }}</p>
<p><strong>Type of work:</strong> {{ $enquiry->display_type_of_work }}</p>
<p><strong>Project location:</strong> {{ $enquiry->display_location }}</p>
<p><strong>Preferred contact:</strong> {{ $enquiry->preferred_contact ?: 'Not specified' }}</p>

<p>A Humelix representative will contact you shortly.</p>

<p>HUMELIX LIMITED</p>
