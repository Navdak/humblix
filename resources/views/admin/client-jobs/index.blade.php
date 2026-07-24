@extends('layouts.admin')
@section('title','Client Jobs')
@section('page_title','Client Job Conversations')
@section('page_subtitle','Private job portals and documented client communication for confirmed work.')
@section('content')
<div class="admin-card">
    <form class="admin-actions" method="GET" style="justify-content:space-between;margin-bottom:18px">
        <div class="admin-actions">
            <input name="search" value="{{ request('search') }}" placeholder="Search job, client, phone, service...">
            <select name="status">
                <option value="">All statuses</option>
                @foreach($statuses as $status)
                    <option value="{{ $status }}" @selected(request('status')===$status)>{{ ucwords(str_replace('_',' ', $status)) }}</option>
                @endforeach
            </select>
            <button class="btn btn-primary">Filter</button>
        </div>
        <a class="btn btn-white" href="{{ route('admin.client-jobs.index') }}">Reset</a>
    </form>

    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Job / Client</th>
                    <th>Service</th>
                    <th>Assigned Engineer</th>
                    <th>Portal</th>
                    <th>Unread</th>
                    <th>Status</th>
                    <th>Updated</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            @forelse($clientJobs as $job)
                @php($latestMessage = $job->latestMessage)
                <tr>
                    <td data-label="Job / Client">
                        <strong>{{ $job->job_reference }}</strong>
                        <small>{{ $job->clientName() }}{{ $job->enquiry?->company_name ? ' · '.$job->enquiry->company_name : '' }}</small>
                        @if($latestMessage)<small>Latest: {{ \Illuminate\Support\Str::limit($latestMessage->body, 56) }}</small>@endif
                    </td>
                    <td data-label="Service">{{ $job->enquiry?->display_type_of_work ?: '—' }}<br><span class="meta">{{ $job->enquiry?->building_type ?: 'Building not set' }}</span></td>
                    <td data-label="Assigned Engineer">
                        @if($job->assignedEngineer)
                            <strong>{{ $job->assignedEngineer->name }}</strong><small>{{ $job->assignedEngineer->field_of_work }}</small>
                        @else
                            <span class="meta">Unassigned</span>
                        @endif
                    </td>
                    <td data-label="Portal"><span class="badge">{{ $job->portal_enabled ? 'Enabled' : 'Disabled' }}</span></td>
                    <td data-label="Unread">
                        @if($job->admin_unread_count > 0)
                            <span class="badge">{{ $job->admin_unread_count }} client</span>
                        @else
                            <span class="meta">None</span>
                        @endif
                    </td>
                    <td data-label="Status"><span class="status-badge status-{{ $job->status }}">{{ $job->statusLabel() }}</span></td>
                    <td data-label="Updated">{{ $job->updated_at->format('M d, Y') }}<br><span class="meta">{{ $job->updated_at->format('H:i') }}</span></td>
                    <td data-label="Actions" class="admin-actions"><a class="btn btn-white" href="{{ route('admin.client-jobs.show', $job) }}">Open</a></td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">
                        @include('admin.partials.empty', [
                            'title' => 'No client job portals yet',
                            'message' => 'Open an enquiry and create a Client Job Portal after HUMELIX confirms it is real work worth tracking.',
                            'actionLabel' => 'View Enquiries',
                            'actionUrl' => route('admin.enquiries.index'),
                        ])
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:18px">{{ $clientJobs->links() }}</div>
</div>
@endsection
