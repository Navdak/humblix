@extends('layouts.admin')
@section('title','Enquiry')
@section('page_title','Enquiry Details')
@section('page_subtitle','Review contact details, project requirements, uploaded files and lead progress.')
@section('page_actions')<a class="btn btn-outline" href="{{ route('admin.enquiries.index') }}">Back to Enquiries</a>@endsection
@section('content')
@php $uploads = $enquiry->uploaded_files ?: []; @endphp
<div class="grid grid-2">
    <div class="admin-card">
        <span class="badge">{{ $enquiry->reference_number ?: 'Pending reference' }}</span>
        <h2 style="margin-top:12px">{{ $enquiry->full_name }}</h2>
        <p class="section-sub">{{ $enquiry->brief_description ?: 'No brief description provided.' }}</p>
        <div class="grid grid-2" style="margin-top:18px">
            <p><strong>Company</strong><br>{{ $enquiry->company_name ?: '—' }}</p>
            <p><strong>Preferred Contact</strong><br>{{ $enquiry->preferred_contact ?: '—' }}</p>
            <p><strong>Phone / WhatsApp</strong><br>{{ $enquiry->phone_whatsapp }}</p>
            <p><strong>Email</strong><br>{{ $enquiry->email ?: '—' }}</p>
            <p><strong>Country</strong><br>{{ $enquiry->country ?: '—' }}</p>
            <p><strong>State / City</strong><br>{{ $enquiry->state_city ?: '—' }}</p>
            <p><strong>Project Location</strong><br>{{ $enquiry->display_location ?: '—' }}</p>
            <p><strong>Submitted Site Address</strong><br>{{ $enquiry->site_address ?: 'Not provided' }}</p>
            <p><strong>Confirmed Site Address</strong><br>{{ $enquiry->confirmed_site_address ?: 'Not confirmed yet' }}</p>
            <p><strong>Building Type</strong><br>{{ $enquiry->building_type ?: '—' }}</p>
            <p><strong>Type of Work</strong><br>{{ $enquiry->display_type_of_work }}</p>
            <p><strong>Urgency</strong><br>{{ $enquiry->urgency ?: 'Normal' }}</p>
            <p><strong>Source</strong><br>{{ $enquiry->source }}</p>
            <p><strong>Created</strong><br>{{ $enquiry->created_at->format('M d, Y H:i') }}</p>
        </div>

        <hr style="border:0;border-top:1px solid var(--line);margin:22px 0">
        <h3>Uploaded Files</h3>
        @if(count($uploads))
            <div class="admin-actions" style="margin-top:10px">
                @foreach($uploads as $file)
                    <a class="btn btn-white" href="{{ asset('storage/'.$file['path']) }}" target="_blank" rel="noopener">{{ $file['original_name'] ?? 'View image' }}</a>
                @endforeach
            </div>
        @elseif($enquiry->attachment_path)
            <a class="btn btn-white" href="{{ asset('storage/'.$enquiry->attachment_path) }}" target="_blank" rel="noopener">View Attachment</a>
        @else
            <p class="section-sub">No uploaded files for this enquiry.</p>
        @endif
    </div>

    <form class="admin-card" method="POST" action="{{ route('admin.enquiries.update',$enquiry) }}">
        @csrf @method('PUT')
        <h2>Lead Management</h2>
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        <div class="form-field">
            <label>Status</label>
            <select name="status">
                @foreach($statuses as $status)
                    <option value="{{ $status }}" @selected(old('status',$enquiry->status)===$status)>{{ ucwords(str_replace('_',' ', $status)) }}</option>
                @endforeach
            </select>
        </div>

        @if($canAssignEngineers)
            <div class="form-field" style="margin-top:14px">
                <label>Assigned Engineer</label>
                <select name="assigned_engineer_id">
                    <option value="">Unassigned</option>
                    @foreach($engineers as $engineer)
                        <option value="{{ $engineer->id }}" @selected((int) old('assigned_engineer_id', $enquiry->assigned_engineer_id) === $engineer->id)>{{ $engineer->assignmentLabel() }} — {{ $engineer->availabilityLabel() }}</option>
                    @endforeach
                </select>
                <small>{{ $engineers->isEmpty() ? 'No active engineer records yet. Add engineers from the Engineers admin page first.' : 'Select from the internal engineer directory. This does not affect the public Team page.' }}</small>
            </div>
            <label class="admin-note" style="display:flex;align-items:flex-start;gap:10px;margin-top:14px">
                <input type="checkbox" name="send_assignment_alert" value="1" style="width:auto;margin-top:4px">
                <span>
                    <strong>Email this engineer about the assignment</strong>
                    <br>
                    <small>Leave unchecked if you prefer to call or message the engineer manually. Engineers must contact HUMELIX Operations before any site visit.</small>
                </span>
            </label>
        @else
            <div class="form-field" style="margin-top:14px">
                <label>Assigned Engineer</label>
                <input value="{{ $enquiry->assignedEngineerLabel() }}" disabled>
                <small>You do not have permission to assign engineers.</small>
            </div>
        @endif

        @if($enquiry->assignedEngineer)
            <div class="admin-note" style="margin-top:14px">
                <strong>{{ $enquiry->assignedEngineer->name }}</strong><br>
                <small>{{ $enquiry->assignedEngineer->field_of_work }}{{ $enquiry->assignedEngineer->region ? ' · '.$enquiry->assignedEngineer->region : '' }}</small>
                @if($enquiry->assignedEngineer->contactSummary())<br><span>{{ $enquiry->assignedEngineer->contactSummary() }}</span>@endif
            </div>
        @elseif($enquiry->assigned_to)
            <div class="admin-note" style="margin-top:14px"><strong>Legacy assignment</strong><br><span>{{ $enquiry->assigned_to }}</span></div>
        @endif

        <div class="form-field" style="margin-top:14px">
            <label>Confirmed Site Address</label>
            <textarea name="confirmed_site_address" rows="3" placeholder="Add or confirm the exact address, landmark and access instructions before dispatch.">{{ old('confirmed_site_address', $enquiry->confirmed_site_address) }}</textarea>
            <small>Admin can add this after calling the client. The engineer email uses this first when available.</small>
        </div>

        <div class="form-field" style="margin-top:14px"><label>Internal Notes</label><textarea name="notes" rows="7">{{ old('notes',$enquiry->notes) }}</textarea></div>
        <button class="btn btn-primary" style="margin-top:18px">Update Enquiry</button>

        <hr style="border:0;border-top:1px solid var(--line);margin:22px 0">
        <h3>Workflow Timeline</h3>
        <dl class="system-list">
            <div><dt>Current status</dt><dd>{{ ucwords(str_replace('_',' ', $enquiry->status)) }}</dd></div>
            <div><dt>Reviewed at</dt><dd>{{ $enquiry->reviewed_at?->format('M d, Y H:i') ?: '—' }}</dd></div>
            <div><dt>Contacted at</dt><dd>{{ $enquiry->contacted_at?->format('M d, Y H:i') ?: '—' }}</dd></div>
            <div><dt>Assigned engineer</dt><dd>{{ $enquiry->assignedEngineerLabel() }}</dd></div>
        </dl>
    </form>
</div>
@endsection
