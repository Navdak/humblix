@extends('layouts.admin')
@section('title','Enquiries')
@section('page_title','Lead Enquiries')
@section('page_subtitle','Track reference numbers, service divisions, contact preferences, assignments and workflow status.')
@section('page_actions')<a class="btn btn-outline" href="{{ route('admin.enquiries.export') }}">Export CSV</a>@endsection
@section('content')
<div class="admin-card">
    <form class="admin-actions" method="GET" style="justify-content:space-between;margin-bottom:18px">
        <div class="admin-actions">
            <input name="search" value="{{ request('search') }}" placeholder="Search ref, name, phone, country, service...">
            <select name="status"><option value="">All statuses</option>@foreach($statuses as $status)<option value="{{ $status }}" @selected(request('status')===$status)>{{ ucwords(str_replace('_',' ', $status)) }}</option>@endforeach</select>
            <button class="btn btn-primary">Filter</button>
        </div>
        <a class="btn btn-white" href="{{ route('admin.enquiries.index') }}">Reset</a>
    </form>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead><tr><th>Reference / Client</th><th>Type of Work</th><th>Location</th><th>Preferred Contact</th><th>Status</th><th>Created</th><th></th></tr></thead>
            <tbody>
            @forelse($enquiries as $enquiry)
                <tr>
                    <td data-label="Reference / Contact"><strong>{{ $enquiry->reference_number ?: 'Pending reference' }}</strong><br><span class="meta">{{ $enquiry->full_name }}{{ $enquiry->company_name ? ' · '.$enquiry->company_name : '' }}</span></td>
                    <td data-label="Type of Work">{{ $enquiry->display_type_of_work }}<br><span class="meta">{{ $enquiry->building_type ?: 'Building not set' }}</span></td>
                    <td data-label="Location">{{ $enquiry->country ?: '—' }}{{ $enquiry->state_city ? ' · '.$enquiry->state_city : '' }}<br><span class="meta">{{ $enquiry->display_location ?: 'No project location' }}</span></td>
                    <td data-label="Preferred Contact">{{ $enquiry->preferred_contact ?: '—' }}<br><span class="meta">{{ $enquiry->phone }}{{ $enquiry->email ? ' · '.$enquiry->email : '' }}</span></td>
                    <td data-label="Status"><span class="badge">{{ ucwords(str_replace('_',' ', $enquiry->status)) }}</span></td>
                    <td data-label="Created">{{ $enquiry->created_at->format('M d, Y') }}<br><span class="meta">{{ $enquiry->created_at->format('H:i') }}</span></td>
                    <td data-label="Actions" class="admin-actions"><a class="btn btn-white" href="{{ route('admin.enquiries.show',$enquiry) }}">Manage</a></td>
                </tr>
            @empty
                <tr><td colspan="7">@include('admin.partials.empty',['title'=>'No enquiries found','message'=>'New customer enquiries will appear here after visitors submit the contact form.'])</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:18px">{{ $enquiries->links() }}</div>
</div>
@endsection
