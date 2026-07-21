@extends('layouts.admin')
@section('title','Safety')
@section('page_title','Safety')
@section('page_subtitle','Manage Safety Framework topics, images, read-more content, and optional videos.')
@section('page_actions')
    <div class="admin-actions">
        <a class="btn btn-outline" href="{{ route('safety') }}" target="_blank" rel="noopener"><x-admin-icon name="external"/> View Safety Centre</a>
        <a class="btn btn-primary" href="{{ route('admin.safety.create') }}"><x-admin-icon name="plus"/> New Safety Topic</a>
    </div>
@endsection
@section('content')
<div class="admin-card">
    <div class="admin-list-intro"><strong>Safety Framework</strong><span>Published and draft safety topics shown on the public Safety Centre.</span></div>
    <table class="admin-table">
        <thead>
            <tr><th>Topic</th><th>Category</th><th>Video</th><th>Sort</th><th>Status</th><th></th></tr>
        </thead>
        <tbody>
            @forelse($topics as $topic)
                <tr>
                    <td data-label="Topic">
                        <strong>{{ $topic->title }}</strong><br>
                        <span class="meta">/safety/{{ $topic->slug }}</span>
                    </td>
                    <td data-label="Category">{{ $topic->category ?: 'Safety Framework' }}</td>
                    <td data-label="Video">{{ $topic->hasVideo() ? $topic->videoPlacementLabel() : '—' }}</td>
                    <td data-label="Sort">{{ $topic->sort_order }}</td>
                    <td data-label="Status"><span class="badge">{{ $topic->status }}</span></td>
                    <td data-label="Actions" class="admin-actions">
                        <a class="btn btn-white" href="{{ route('admin.safety.edit',$topic) }}">Edit</a>
                        @if(auth()->user()?->canDeleteRecords())
                            <form method="POST" action="{{ route('admin.safety.destroy',$topic) }}" onsubmit="return confirm('Delete this safety topic?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-outline" style="color:#b91c1c">Delete</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">@include('admin.partials.empty',['title'=>'No safety topics yet','message'=>'Create the first editable Safety Framework topic.','actionUrl'=>route('admin.safety.create'),'actionLabel'=>'New Safety Topic','icon'=>'safety'])</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div style="margin-top:18px">{{ $topics->links() }}</div>
</div>
@endsection
