@extends('layouts.admin')
@section('title','Projects')
@section('page_title','Projects')
@section('page_subtitle','Manage installation case studies, results, and featured work.')
@section('page_actions')<a class="btn btn-primary" href="{{ route('admin.projects.create') }}"><x-admin-icon name="plus"/> New Project</a>@endsection
@section('content')
<div class="admin-card">
    <div class="admin-list-intro"><strong>Project library</strong><span>Published and draft case studies.</span></div>
    <table class="admin-table"><thead><tr><th>Project</th><th>Sector</th><th>Service</th><th>Country / Location</th><th>Featured</th><th>Status</th><th></th></tr></thead><tbody>
    @forelse($projects as $project)<tr><td data-label="Project"><strong>{{ $project->title }}</strong><br><span class="meta">{{ $project->location }} · {{ $project->system_type }}</span></td><td data-label="Sector">{{ $project->sector }}</td><td data-label="Service">{{ $project->service_division ?: '—' }}</td><td data-label="Country / Location">{{ $project->country ?: '—' }}</td><td data-label="Featured">{{ $project->is_featured ? 'Yes' : 'No' }}</td><td data-label="Status"><span class="badge">{{ $project->status }}</span></td><td data-label="Actions" class="admin-actions"><a class="btn btn-white" href="{{ route('admin.projects.edit',$project) }}">Edit</a><form method="POST" action="{{ route('admin.projects.destroy',$project) }}" onsubmit="return confirm('Delete this project?')">@csrf @method('DELETE')<button class="btn btn-outline" style="color:#b91c1c">Delete</button></form></td></tr>@empty<tr><td colspan="7">@include('admin.partials.empty',['title'=>'No projects yet','message'=>'Create a case study to start building project proof.','actionUrl'=>route('admin.projects.create'),'actionLabel'=>'New Project','icon'=>'projects'])</td></tr>@endforelse
    </tbody></table>
    <div style="margin-top:18px">{{ $projects->links() }}</div>
</div>
@endsection
