@extends('layouts.admin')
@section('title','Careers')
@section('page_title','Careers')
@section('page_subtitle','Manage job openings and recruitment publishing status.')
@section('page_actions')<a class="btn btn-primary" href="{{ route('admin.jobs.create') }}"><x-admin-icon name="plus"/> New Job</a>@endsection
@section('content')
<div class="admin-card">
    <div class="admin-list-intro"><strong>Job openings</strong><span>Open jobs with a publish date appear publicly.</span></div>
    <table class="admin-table"><thead><tr><th>Role</th><th>Department</th><th>Location</th><th>Type</th><th>Status</th><th>Published</th><th></th></tr></thead><tbody>
    @forelse($jobs as $job)
        <tr><td><strong>{{ $job->title }}</strong><small>{{ $job->closing_date ? 'Closes '.$job->closing_date->format('M j, Y') : 'No closing date' }}</small></td><td>{{ $job->department ?: '—' }}</td><td>{{ $job->location ?: '—' }}</td><td>{{ $job->employment_type ?: '—' }}</td><td><span class="status-badge status-{{ $job->status }}">{{ $job->status }}</span></td><td>{{ $job->published_at ? $job->published_at->format('M j, Y') : '—' }}</td><td class="admin-actions"><a class="btn btn-white" href="{{ route('admin.jobs.edit',$job) }}">Edit</a><form method="POST" action="{{ route('admin.jobs.destroy',$job) }}" onsubmit="return confirm('Delete this job opening?')">@csrf @method('DELETE')<button class="btn btn-outline" style="color:#b91c1c">Delete</button></form></td></tr>
    @empty
        <tr><td colspan="7">@include('admin.partials.empty',['title'=>'No jobs yet','message'=>'Add real openings only when recruitment details are approved.'])</td></tr>
    @endforelse
    </tbody></table>
    <div style="margin-top:18px">{{ $jobs->links() }}</div>
</div>
@endsection
