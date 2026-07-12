@extends('layouts.admin')
@section('title','Videos')
@section('page_title','Videos')
@section('page_subtitle','Manage field work, project, safety and vendor video content.')
@section('page_actions')<a class="btn btn-primary" href="{{ route('admin.videos.create') }}"><x-admin-icon name="plus"/> New Video</a>@endsection
@section('content')
<div class="admin-card">
    <div class="admin-list-intro"><strong>Video library</strong><span>Drafts remain private; published videos can appear publicly.</span></div>
    <table class="admin-table"><thead><tr><th>Thumb</th><th>Video</th><th>Category</th><th>Type</th><th>Status</th><th>Featured</th><th>Related</th><th>Published</th><th></th></tr></thead><tbody>
    @forelse($videos as $video)
        <tr>
            <td data-label="Thumbnail">@if($video->thumbnailUrl())<img src="{{ $video->thumbnailUrl() }}" alt="{{ $video->title }}" class="admin-thumb">@else<span class="badge">No thumb</span>@endif</td>
            <td data-label="Title"><strong>{{ $video->title }}</strong><small>{{ $video->caption ?: 'No caption' }}</small></td>
            <td data-label="Category">{{ $video->category ?: '—' }}</td>
            <td data-label="Type">{{ ucfirst($video->video_type) }}</td>
            <td data-label="Status"><span class="status-badge status-{{ $video->status }}">{{ $video->status }}</span></td>
            <td data-label="Featured">{{ $video->is_featured ? 'Yes' : 'No' }}</td>
            <td data-label="Related"><small>{{ $video->related_service ?: ($video->project?->title ?: ($video->branch?->name ?: ($video->equipment?->name ?: '—'))) }}</small></td>
            <td data-label="Published">{{ $video->published_at ? $video->published_at->format('M j, Y') : '—' }}</td>
            <td data-label="Actions" class="admin-actions"><a class="btn btn-white" href="{{ route('admin.videos.edit',$video) }}">Edit</a><form method="POST" action="{{ route('admin.videos.destroy',$video) }}" onsubmit="return confirm('Delete this video?')">@csrf @method('DELETE')<button class="btn btn-outline" style="color:#b91c1c">Delete</button></form></td>
        </tr>
    @empty
        <tr><td colspan="9">@include('admin.partials.empty',['title'=>'No videos yet','message'=>'Add verified videos or safe external video links when ready.','actionUrl'=>route('admin.videos.create'),'actionLabel'=>'New Video','icon'=>'videos'])</td></tr>
    @endforelse
    </tbody></table>
    <div style="margin-top:18px">{{ $videos->links() }}</div>
</div>
@endsection
