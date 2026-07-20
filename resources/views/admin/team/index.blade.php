@extends('layouts.admin')
@section('title','Team Members')
@section('page_title','Team Members')
@section('page_subtitle','Manage engineers, delegates, support staff, and regional contacts.')
@section('page_actions')<a class="btn btn-primary" href="{{ route('admin.team.create') }}"><x-admin-icon name="plus"/> New Member</a>@endsection
@section('content')
<div class="admin-card">
    <div class="admin-list-intro"><strong>Team directory</strong><span>Control public visibility and profile order.</span></div>
    <table class="admin-table"><thead><tr><th>Member</th><th>Role</th><th>Region</th><th>Visible</th><th></th></tr></thead><tbody>
    @forelse($members as $member)<tr><td><strong>{{ $member->name }}</strong><br><span class="meta">{{ $member->experience }}</span></td><td>{{ $member->role }}</td><td>{{ $member->region }}</td><td>{{ $member->is_visible ? 'Yes' : 'No' }}</td><td class="admin-actions"><a class="btn btn-white" href="{{ route('admin.team.edit',$member) }}">Edit</a>@if(auth()->user()?->canDeleteRecords())<form method="POST" action="{{ route('admin.team.destroy',$member) }}" onsubmit="return confirm('Delete this team member?')">@csrf @method('DELETE')<button class="btn btn-outline" style="color:#b91c1c">Delete</button></form>@endif</td></tr>@empty<tr><td colspan="5">No team members yet.</td></tr>@endforelse
    </tbody></table>
    <div style="margin-top:18px">{{ $members->links() }}</div>
</div>
@endsection
