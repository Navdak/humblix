@extends('layouts.admin')
@section('title','Media Library')
@section('page_title','Media Library')
@section('page_subtitle','Upload and manage images, documents, and project media.')
@section('content')
<form class="admin-card" method="POST" action="{{ route('admin.media.store') }}" enctype="multipart/form-data" style="margin-bottom:22px">
    @csrf
    <div class="form-grid"><div class="form-field"><label>Upload File</label><input type="file" name="file" required></div><div class="form-field"><label>Alt Text / Description</label><input name="alt_text" placeholder="Describe the file"></div></div>
    <button class="btn btn-primary" style="margin-top:16px">Upload</button>
</form>
<div class="grid grid-4">
@forelse($assets as $asset)
    <div class="card">
        @if(str_starts_with($asset->mime_type, 'image/'))<div class="image-frame"><img src="{{ asset('storage/'.$asset->file_path) }}" alt="{{ $asset->alt_text }}"></div>@else<div class="image-frame" style="display:grid;place-items:center;font-weight:900">FILE</div>@endif
        <h3 style="word-break:break-word">{{ $asset->file_name }}</h3>
        <p>{{ $asset->alt_text }}</p>
        <div class="admin-actions" style="margin-top:12px"><a class="btn btn-white" href="{{ asset('storage/'.$asset->file_path) }}" target="_blank">Open</a><form method="POST" action="{{ route('admin.media.destroy',$asset) }}" onsubmit="return confirm('Delete this media asset?')">@csrf @method('DELETE')<button class="btn btn-outline" style="color:#b91c1c">Delete</button></form></div>
    </div>
@empty
    <div class="admin-card">No media uploaded yet.</div>
@endforelse
</div>
<div style="margin-top:18px">{{ $assets->links() }}</div>
@endsection
