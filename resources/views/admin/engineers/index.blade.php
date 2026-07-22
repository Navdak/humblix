@extends('layouts.admin')
@section('title','Engineers')
@section('page_title','Engineers')
@section('page_subtitle','Internal field personnel directory for enquiry assignment, contact tracking and operational follow-up.')
@section('page_actions')
    <a class="btn btn-primary" href="{{ route('admin.engineers.create') }}"><x-admin-icon name="plus"/> New Engineer</a>
@endsection
@section('content')
@if(auth()->user()?->canDeleteRecords())
    <form class="admin-card" method="POST" action="{{ route('admin.engineers.assignment-contact') }}" style="margin-bottom:18px">
        @csrf @method('PATCH')
        <div class="admin-list-intro">
            <strong>Engineer Assignment Contact</strong>
            <span>Shown inside assignment emails so engineers know who to contact before any site visit.</span>
        </div>
        <div class="admin-note" style="margin-bottom:16px">
            Engineers should always confirm schedule, exact location and client readiness with HUMELIX Operations before visiting a site.
        </div>
        <div class="form-grid">
            <div class="form-field">
                <label>Contact Name / Team</label>
                <input name="assignment_contact_name" value="{{ old('assignment_contact_name', $assignmentContact['assignment_contact_name'] ?? 'HUMELIX Operations Team') }}" placeholder="HUMELIX Operations Team">
            </div>
            <div class="form-field">
                <label>Phone</label>
                <input name="assignment_contact_phone" value="{{ old('assignment_contact_phone', $assignmentContact['assignment_contact_phone'] ?? '') }}" placeholder="+234...">
            </div>
            <div class="form-field">
                <label>WhatsApp</label>
                <input name="assignment_contact_whatsapp" value="{{ old('assignment_contact_whatsapp', $assignmentContact['assignment_contact_whatsapp'] ?? '') }}" placeholder="+234...">
            </div>
            <div class="form-field">
                <label>Email</label>
                <input type="email" name="assignment_contact_email" value="{{ old('assignment_contact_email', $assignmentContact['assignment_contact_email'] ?? '') }}" placeholder="operations@humelix.com">
            </div>
            <div class="form-field full">
                <label>Instruction Note</label>
                <textarea name="assignment_contact_note" rows="3" placeholder="Call this line before site visits or if project details are unclear.">{{ old('assignment_contact_note', $assignmentContact['assignment_contact_note'] ?? 'Contact HUMELIX Operations before visiting any client site to confirm schedule, exact location and client readiness.') }}</textarea>
            </div>
        </div>
        <button class="btn btn-primary" type="submit" style="margin-top:18px">Save Assignment Contact</button>
    </form>
@endif

<div class="admin-card">
    <div class="admin-list-intro">
        <strong>Internal engineer directory</strong>
        <span>These records are private to admin. They do not appear on the public Team page unless separately added there.</span>
    </div>

    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Engineer</th>
                    <th>Field</th>
                    <th>Region</th>
                    <th>Contact</th>
                    <th>Status</th>
                    <th>Assigned Leads</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            @forelse($engineers as $engineer)
                <tr>
                    <td data-label="Engineer">
                        <div class="admin-person-cell">
                            <img loading="lazy" decoding="async" width="48" height="48" src="{{ $engineer->photoUrl() }}" alt="{{ $engineer->name }}">
                            <span><strong>{{ $engineer->name }}</strong><br><span class="meta">{{ $engineer->title ?: 'Field personnel' }}</span></span>
                        </div>
                    </td>
                    <td data-label="Field">{{ $engineer->field_of_work }}</td>
                    <td data-label="Region">{{ $engineer->region ?: '—' }}</td>
                    <td data-label="Contact" class="admin-contact-cell">
                        {{ $engineer->phone ?: ($engineer->whatsapp ?: '—') }}
                        @if($engineer->email)<br><span class="meta">{{ $engineer->email }}</span>@endif
                    </td>
                    <td data-label="Status"><span class="badge">{{ $engineer->availabilityLabel() }}</span></td>
                    <td data-label="Assigned Leads">{{ $engineer->assigned_enquiries_count }}</td>
                    <td data-label="Actions" class="admin-actions">
                        <a class="btn btn-white" href="{{ route('admin.engineers.edit', $engineer) }}">Edit</a>
                        @if(auth()->user()?->canDeleteRecords())
                            <form method="POST" action="{{ route('admin.engineers.destroy', $engineer) }}" onsubmit="return confirm('Delete this engineer record? Existing enquiries will become unassigned.')">
                                @csrf @method('DELETE')
                                <button class="btn btn-outline" style="color:#b91c1c">Delete</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">
                        @include('admin.partials.empty', [
                            'title' => 'No engineers yet',
                            'message' => 'Create internal engineer records so enquiries can be assigned from a clean dropdown instead of free text.',
                            'actionLabel' => 'Add Engineer',
                            'actionUrl' => route('admin.engineers.create'),
                        ])
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:18px">{{ $engineers->links() }}</div>
</div>
@endsection
