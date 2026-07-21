@csrf
@if($topic->exists) @method('PUT') @endif
@php
    $summaryText = old('summary_points_text');
    if ($summaryText === null) {
        $summaryText = implode("\n", $topic->summary_points ?: []);
    }
@endphp
<div class="form-grid">
    <div class="form-field">
        <label>Title</label>
        <input name="title" value="{{ old('title',$topic->title) }}" required>
    </div>
    <div class="form-field">
        <label>Slug</label>
        <input name="slug" value="{{ old('slug',$topic->slug) }}" placeholder="auto-generated if empty">
    </div>
    <div class="form-field">
        <label>Category</label>
        <input name="category" value="{{ old('category',$topic->category ?: 'Safety Framework') }}" placeholder="Safety Framework">
    </div>
    <div class="form-field">
        <label>Status</label>
        <select name="status" required>
            @foreach($statuses as $status)
                <option value="{{ $status }}" @selected(old('status',$topic->status ?: 'draft') === $status)>{{ ucfirst($status) }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-field">
        <label>Sort Order</label>
        <input type="number" min="0" name="sort_order" value="{{ old('sort_order',$topic->sort_order ?? 0) }}">
    </div>
    <div class="form-field">
        <label>Published At</label>
        <input type="datetime-local" name="published_at" value="{{ old('published_at', optional($topic->published_at)->format('Y-m-d\TH:i')) }}">
    </div>
    <div class="form-field full">
        <label>Short Description</label>
        <textarea name="excerpt" rows="3" maxlength="500" required>{{ old('excerpt',$topic->excerpt) }}</textarea>
        <small>Shown on the Safety Framework card and read-more hero.</small>
    </div>
    <div class="form-field full">
        <label>Summary Points <span class="meta">one point per line</span></label>
        <textarea name="summary_points_text" rows="5" placeholder="Identify site hazards before work starts&#10;Plan access, isolation and work sequence">{{ $summaryText }}</textarea>
        <small>These appear as checklist bullets on the public card and topic page. The first 8 lines are used.</small>
    </div>
    <div class="form-field full">
        <label>Full Content</label>
        <textarea name="content" rows="12" required placeholder="<p>Explain what this safety topic means on site...</p>">{{ old('content',$topic->content) }}</textarea>
        <small>You can use simple HTML such as paragraphs, headings, and lists.</small>
    </div>
    <div class="form-field">
        <label>Topic Image</label>
        <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
        <small>JPG, PNG or WebP. Max 5MB.</small>
    </div>
    <div class="form-field">
        <label>Current Image</label>
        @if($topic->image_path)
            <div class="admin-note">
                <strong>{{ $topic->image_path }}</strong>
                @if($topic->hasUploadedImage())
                    <label style="display:flex;align-items:center;gap:8px;margin-top:10px">
                        <input type="checkbox" name="remove_image" value="1" style="width:auto">
                        Remove uploaded image and use generated fallback
                    </label>
                @endif
            </div>
        @else
            <div class="admin-note">No custom image yet. The generated safety fallback will be used.</div>
        @endif
    </div>
    <div class="form-field full">
        <label>Optional Safety Video URL <span class="badge">Recommended: YouTube link</span></label>
        <input type="url" name="video_url" value="{{ old('video_url',$topic->video_url) }}" placeholder="YouTube, YouTube Shorts, Vimeo, MP4 or WebM URL">
        <small>Use YouTube or YouTube Shorts for best shared-hosting performance. Leave empty if this topic does not need video.</small>
    </div>
    <div class="form-field">
        <label>Video Title</label>
        <input name="video_title" maxlength="160" value="{{ old('video_title',$topic->video_title) }}" placeholder="Optional title shown above video">
    </div>
    <div class="form-field">
        <label>Video Placement</label>
        <select name="video_placement">
            @foreach($videoPlacements as $value => $label)
                <option value="{{ $value }}" @selected(old('video_placement',$topic->video_placement ?: 'end') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        <small>Choose where the video appears on the safety read-more page.</small>
    </div>
    <div class="form-field full">
        <label>Video Caption</label>
        <textarea name="video_caption" rows="3" maxlength="500" placeholder="Optional short context for the video">{{ old('video_caption',$topic->video_caption) }}</textarea>
    </div>
    <div class="form-field">
        <label>CTA Label</label>
        <input name="cta_label" value="{{ old('cta_label',$topic->cta_label ?: 'Request Site Inspection') }}" maxlength="120">
    </div>
    <div class="form-field">
        <label>CTA URL</label>
        <input name="cta_url" value="{{ old('cta_url',$topic->cta_url ?: route('contact').'?service=Maintenance') }}" maxlength="255">
    </div>
</div>
@if($topic->exists && $topic->hasVideo())
    <p class="field-help" style="margin-top:14px">Current video: {{ $topic->videoPlacementLabel() }} · <a href="{{ $topic->video_url }}" target="_blank" rel="noopener">Open source link</a></p>
@endif
<button class="btn btn-primary" type="submit" style="margin-top:20px">Save Safety Topic</button>
