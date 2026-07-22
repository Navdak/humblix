@extends('layouts.admin')
@section('title','Media Library')
@section('page_title','Media Library')
@section('page_subtitle','Upload and manage images, documents, project media, and article inline images.')
@section('content')
<form class="admin-card" method="POST" action="{{ route('admin.media.store') }}" enctype="multipart/form-data" style="margin-bottom:22px">
    @csrf
    <div class="form-grid"><div class="form-field"><label>Upload File</label><input type="file" name="file" required></div><div class="form-field"><label>Alt Text / Description</label><input name="alt_text" placeholder="Describe the file"></div></div>
    <button class="btn btn-primary" style="margin-top:16px">Upload</button>
</form>
<div class="admin-note" style="margin-bottom:18px">
    <strong>Article inline images:</strong> images inserted through the article editor are saved here.
    If a file is marked as used in articles, remove it from the article content before deleting it here.
    <br>
    <strong>How to reuse an uploaded image:</strong> click <strong>Copy URL</strong>, edit the article, click the TinyMCE image button, paste the copied URL into the source field, then save the article.
</div>
<div class="grid grid-4">
@forelse($assets as $asset)
    <div class="card">
        @php($publicUrl = asset('storage/'.$asset->file_path))
        @if(str_starts_with((string) $asset->mime_type, 'image/') && $asset->file_exists)
            <div class="image-frame"><img loading="lazy" decoding="async" width="800" height="500" src="{{ $publicUrl }}" alt="{{ $asset->alt_text }}"></div>
        @elseif(str_starts_with((string) $asset->mime_type, 'image/'))
            <div class="image-frame" style="display:grid;place-items:center;font-weight:900">MISSING IMAGE</div>
        @else
            <div class="image-frame" style="display:grid;place-items:center;font-weight:900">FILE</div>
        @endif
        <h3 style="word-break:break-word">{{ $asset->file_name }}</h3>
        <p>{{ $asset->alt_text }}</p>
        <p class="meta">
            {{ number_format(((int) $asset->size_bytes) / 1024, 1) }} KB
            @if(str_starts_with((string) $asset->file_path, 'article-inline-images/'))
                · Article inline image
            @endif
        </p>
        @if($asset->file_exists)
            <div class="admin-note" style="margin-top:10px">
                <strong>Public URL</strong>
                <input value="{{ $publicUrl }}" readonly data-media-url-input style="margin-top:8px;font-size:.82rem">
                <button class="btn btn-white" type="button" data-copy-media-url="{{ $publicUrl }}" style="margin-top:8px;width:100%">Copy URL</button>
            </div>
        @endif
        <div class="admin-note" style="margin-top:10px">
            <strong>{{ (int) $asset->article_usage_count }}</strong>
            {{ (int) $asset->article_usage_count === 1 ? 'article uses this file' : 'articles use this file' }}.
            @if(! $asset->file_exists)<br><span style="color:#b91c1c">The file record exists, but the storage file is missing.</span>@endif
        </div>
        <div class="admin-actions" style="margin-top:12px">
            @if($asset->file_exists)<a class="btn btn-white" href="{{ $publicUrl }}" target="_blank">Open</a>@endif
            @if(auth()->user()?->canDeleteRecords())
                <form method="POST" action="{{ route('admin.media.destroy',$asset) }}" onsubmit="return confirm('{{ (int) $asset->article_usage_count > 0 ? 'This file is used in article content and deletion will be blocked. Remove it from the article first.' : 'Delete this media asset?' }}')">
                    @csrf @method('DELETE')
                    <button class="btn btn-outline" style="color:#b91c1c" @disabled((int) $asset->article_usage_count > 0)>Delete</button>
                </form>
            @endif
        </div>
    </div>
@empty
    <div class="admin-card">No media uploaded yet.</div>
@endforelse
</div>
<div style="margin-top:18px">{{ $assets->links() }}</div>
@push('scripts')
<script>
document.addEventListener('click', async (event) => {
    const button = event.target.closest('[data-copy-media-url]');
    if (!button) return;

    const url = button.dataset.copyMediaUrl || '';
    const originalLabel = button.textContent;

    try {
        if (navigator.clipboard && window.isSecureContext) {
            await navigator.clipboard.writeText(url);
        } else {
            const tempInput = document.createElement('input');
            tempInput.value = url;
            tempInput.setAttribute('readonly', 'readonly');
            tempInput.style.position = 'fixed';
            tempInput.style.left = '-9999px';
            document.body.appendChild(tempInput);
            tempInput.select();
            document.execCommand('copy');
            tempInput.remove();
        }

        button.textContent = 'Copied';
        setTimeout(() => button.textContent = originalLabel, 1800);
    } catch (error) {
        const input = button.closest('.admin-note')?.querySelector('[data-media-url-input]');
        input?.focus();
        input?.select();
        button.textContent = 'Select URL';
        setTimeout(() => button.textContent = originalLabel, 2200);
    }
});
</script>
@endpush
@endsection
