@extends('layouts.admin')
@section('title','Branches')
@section('page_title','Branches')
@section('page_subtitle','Manage verified country and city branch contact foundations.')
@section('page_actions')<a class="btn btn-primary" href="{{ route('admin.branches.create') }}"><x-admin-icon name="plus"/> New Branch</a>@endsection
@section('content')
<div class="admin-card">
    <div class="admin-list-intro"><strong>Branch directory</strong><span>Only published active branches appear publicly.</span></div>
    <table class="admin-table"><thead><tr><th>Branch</th><th>Location</th><th>Contact</th><th>Manager</th><th>Status</th><th>Published</th><th></th></tr></thead><tbody>
    @forelse($branches as $branch)
        <tr><td><strong>{{ $branch->name }}</strong><small>{{ $branch->service_coverage ?: 'No coverage summary yet' }}</small></td><td>{{ $branch->state_city ?: '—' }}<br><small>{{ $branch->country }}</small></td><td>{{ $branch->phone ?: '—' }}<small>{{ $branch->email ?: '' }}</small></td><td>{{ $branch->manager_name ?: '—' }}</td><td><span class="status-badge status-{{ $branch->status }}">{{ $branch->status }}</span></td><td>{{ $branch->is_published ? 'Yes' : 'No' }}</td><td class="admin-actions"><a class="btn btn-white" href="{{ route('admin.branches.edit',$branch) }}">Edit</a>@if(auth()->user()?->canDeleteRecords())<form method="POST" action="{{ route('admin.branches.destroy',$branch) }}" onsubmit="return confirm('Delete this branch?')">@csrf @method('DELETE')<button class="btn btn-outline" style="color:#b91c1c">Delete</button></form>@endif</td></tr>
    @empty
        <tr><td colspan="7">@include('admin.partials.empty',['title'=>'No branches yet','message'=>'Add verified branch records when real contact details are ready.'])</td></tr>
    @endforelse
    </tbody></table>
    <div style="margin-top:18px">{{ $branches->links() }}</div>
</div>
@endsection
