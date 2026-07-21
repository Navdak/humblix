@csrf
@if($article->exists) @method('PUT') @endif
<div class="form-grid" x-data="articleLinks({ initial: @js(old('links', $article->exists ? $article->relatedLinks->map(fn($link) => ['link_text' => $link->link_text, 'url' => $link->url])->values()->toArray() : [])) })">
    <div class="form-field"><label>Title</label><input name="title" value="{{ old('title',$article->title) }}" required></div>
    <div class="form-field"><label>Slug</label><input name="slug" value="{{ old('slug',$article->slug) }}" placeholder="auto-generated if empty"></div>
    <div class="form-field">
        <label>Category</label>
        <select name="category" required>
            @foreach($categories ?? \App\Models\Article::CATEGORIES as $value => $label)
                <option value="{{ $value }}" @selected(old('category', $article->category ?: 'general') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        <small>Used for public resource filtering and related article suggestions.</small>
    </div>
    <div class="form-field full"><label>SEO Excerpt <span class="meta">max 160 chars</span></label><textarea name="excerpt" rows="3" maxlength="160" required>{{ old('excerpt',$article->excerpt) }}</textarea></div>
    <div class="form-field"><label>Featured Image</label><input type="file" name="featured_image" accept="image/*"></div>
    <div class="form-field"><label>Status</label><select name="status"><option value="draft" @selected(old('status',$article->status ?: 'draft')==='draft')>Draft</option><option value="published" @selected(old('status',$article->status ?: 'draft')==='published')>Published</option></select></div>
    <div class="form-field full">
        <label>PDF Attachment <span class="meta">optional, max 10MB</span></label>
        <input type="file" name="pdf_attachment" accept="application/pdf,.pdf">
        <small>Use PDFs for long guides, manuals, checklists, brochures, or downloadable resources. Keep the web article readable and attach the full guide when needed.</small>
        @if($article->exists && $article->hasPdfAttachment())
            <div class="admin-note" style="margin-top:10px">
                <strong>Current PDF:</strong> <a href="{{ $article->pdfUrl() }}" target="_blank" rel="noopener">View uploaded PDF</a>
                <label style="display:flex;align-items:center;gap:8px;margin-top:10px">
                    <input type="checkbox" name="remove_pdf" value="1" style="width:auto">
                    Remove current PDF attachment
                </label>
            </div>
        @endif
    </div>
    <div class="form-field full">
        <label>Optional Article Video URL <span class="badge">Recommended: YouTube link</span></label>
        <input type="url" name="video_url" value="{{ old('video_url', $article->video_url) }}" placeholder="YouTube, YouTube Shorts, Vimeo, MP4 or WebM URL">
        <small>Use this when an article needs a supporting video inside the read-more page. YouTube and YouTube Shorts are best for hosting performance.</small>
    </div>
    <div class="form-field">
        <label>Video Title</label>
        <input name="video_title" maxlength="160" value="{{ old('video_title', $article->video_title) }}" placeholder="Optional title shown above the video">
    </div>
    <div class="form-field">
        <label>Video Placement</label>
        <select name="video_placement">
            @foreach($videoPlacements ?? \App\Models\Article::VIDEO_PLACEMENTS as $value => $label)
                <option value="{{ $value }}" @selected(old('video_placement', $article->video_placement ?: 'end') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        <small>Choose where the video appears in the public article page.</small>
    </div>
    <div class="form-field full">
        <label>Video Caption</label>
        <textarea name="video_caption" rows="3" maxlength="300" placeholder="Optional short context for the video">{{ old('video_caption', $article->video_caption) }}</textarea>
        @if($article->exists && $article->hasArticleVideo())
            <div class="admin-note" style="margin-top:10px">
                <strong>Current article video:</strong> {{ $article->articleVideoPlacementLabel() }}
                <a href="{{ $article->video_url }}" target="_blank" rel="noopener" style="margin-left:8px">Open source link</a>
            </div>
        @endif
    </div>
    <div class="form-field full">
        <label>Content</label>
        <textarea id="content" name="content" rows="16" aria-describedby="article-content-help article-content-error">{{ old('content',$article->content) }}</textarea>
        <div id="article-content-error" class="admin-inline-error" hidden data-article-content-error>Article content is required before saving.</div>
        <small>
            <span id="article-content-help">Recommended: 1,500–3,000 words. Maximum: {{ number_format(\App\Models\Article::MAX_WORD_COUNT) }} words.</span>
            For longer guides, split manually into multiple focused articles or attach a PDF.
            <strong><span data-article-word-count>0</span> words</strong>
        </small>
    </div>
    <div class="form-field full">
        <div class="admin-actions" style="justify-content:space-between"><label>Related Links</label><button class="btn btn-white" type="button" @click="add">Add Link</button></div>
        <template x-for="(link, index) in links" :key="index">
            <div class="grid grid-2" style="margin-top:10px">
                <input :name="`links[${index}][link_text]`" x-model="link.link_text" placeholder="Link text">
                <div class="admin-actions"><input :name="`links[${index}][url]`" x-model="link.url" placeholder="https://example.com"><button class="btn btn-outline" type="button" @click="remove(index)">Remove</button></div>
            </div>
        </template>
    </div>
</div>
<button class="btn btn-primary" type="submit" style="margin-top:20px">Save Article</button>
@push('head')
<script src="https://cdn.tiny.cloud/1/{{ config('tinymce.api_key', 'no-api-key') }}/tinymce/8/tinymce.min.js" referrerpolicy="origin"></script>
@endpush
@push('scripts')
<script>
function articleLinks({initial}) { return { links: initial.length ? initial : [], add(){ this.links.push({link_text:'', url:''}) }, remove(i){ this.links.splice(i,1) } } }
function articleWordCountFromHtml(html) {
    const text = (html || '').replace(/<[^>]*>/g, ' ').replace(/&nbsp;/gi, ' ').trim();
    if (!text) return 0;
    return (text.match(/[\p{L}\p{N}]+(?:['-][\p{L}\p{N}]+)*/gu) || []).length;
}
function updateArticleWordCount() {
    const target = document.querySelector('[data-article-word-count]');
    const textarea = document.querySelector('#content');
    if (!target || !textarea) return;
    const html = window.tinymce?.get('content') ? window.tinymce.get('content').getContent() : textarea.value;
    const count = articleWordCountFromHtml(html);
    target.textContent = count.toLocaleString();
    target.style.color = count > {{ \App\Models\Article::MAX_WORD_COUNT }} ? '#b91c1c' : '';
}
function syncArticleEditorContent() {
    const editor = window.tinymce?.get('content');
    if (editor) editor.save();
}
function setArticleContentError(message = '') {
    const error = document.querySelector('[data-article-content-error]');
    const textarea = document.querySelector('#content');
    if (!error || !textarea) return;
    error.textContent = message || 'Article content is required before saving.';
    error.hidden = !message;
    textarea.setAttribute('aria-invalid', message ? 'true' : 'false');
}
if (window.tinymce) {
    tinymce.init({
        selector:'#content',
        height:460,
        menubar:false,
        plugins:'advlist autolink autoresize code fullscreen help image link lists media preview quickbars searchreplace table visualblocks wordcount',
        toolbar:'undo redo | blocks | bold italic | bullist numlist | link image media table | searchreplace visualblocks fullscreen preview | removeformat code help',
        paste_as_text:false,
        promotion:false,
        setup(editor) {
            editor.on('init keyup change input SetContent', () => {
                syncArticleEditorContent();
                updateArticleWordCount();
                if (articleWordCountFromHtml(editor.getContent()) > 0) setArticleContentError('');
            });
        }
    });
}
document.querySelector('#content')?.addEventListener('input', updateArticleWordCount);
document.addEventListener('DOMContentLoaded', updateArticleWordCount);
document.querySelector('#content')?.closest('form')?.addEventListener('submit', (event) => {
    syncArticleEditorContent();
    const textarea = document.querySelector('#content');
    const count = articleWordCountFromHtml(textarea?.value || '');
    if (count < 1) {
        event.preventDefault();
        setArticleContentError('Article content is required before saving.');
        const editor = window.tinymce?.get('content');
        if (editor) {
            editor.focus();
            editor.getContainer()?.scrollIntoView({ behavior: 'smooth', block: 'center' });
        } else {
            textarea?.focus();
        }
    } else {
        setArticleContentError('');
    }
});
setTimeout(updateArticleWordCount, 800);
</script>
@endpush
