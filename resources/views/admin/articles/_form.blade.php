@csrf
@if($article->exists) @method('PUT') @endif
<div class="form-grid">
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
    <div class="form-field">
        <label>Featured Image <span class="meta">JPG, PNG or WebP, max 4MB</span></label>
        <input type="file" name="featured_image" accept="image/*">
        <small>Upload a prepared web image. For best performance, keep article images around 1000-1200px wide.</small>
    </div>
    <div class="form-field"><label>Status</label><select name="status"><option value="draft" @selected(old('status',$article->status ?: 'draft')==='draft')>Draft</option><option value="published" @selected(old('status',$article->status ?: 'draft')==='published')>Published</option></select></div>
    <div class="form-field full">
        <label>PDF Attachment <span class="meta">optional, max 10MB</span></label>
        <input type="file" name="pdf_attachment" accept="application/pdf,.pdf">
        <small>Use PDFs for long guides, manuals, checklists, brochures, or downloadable resources. Keep the file under 10MB. On production, PHP upload limits must allow the combined image + PDF request.</small>
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
            <span id="article-content-help">Recommended: 1,500-3,000 words. Maximum: {{ number_format(\App\Models\Article::MAX_WORD_COUNT) }} words.</span>
            For longer guides, split manually into multiple focused articles or attach a PDF.
            <strong><span data-article-word-count>0</span> words</strong>
        </small>
        <div class="admin-note" style="margin-top:12px">
            <strong>Inline images:</strong> use the editor image button to place supporting images anywhere inside the article.
            Recommended: compressed JPG/WebP, 1000-1200px wide, max 4MB per image.
            Uploaded inline images are saved to the Media Library for later review and cleanup. To reuse an already uploaded image, copy its public URL from Media Library and paste it into the editor image source field.
        </div>
    </div>
</div>
<button class="btn btn-primary" type="submit" data-article-submit style="margin-top:20px">Save Article</button>
@push('head')
<script src="https://cdn.tiny.cloud/1/{{ config('tinymce.api_key', 'no-api-key') }}/tinymce/8/tinymce.min.js" referrerpolicy="origin"></script>
@endpush
@push('scripts')
<script>
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
function articleContentHasPendingImages(html) {
    const parser = new DOMParser();
    const documentFragment = parser.parseFromString(html || '', 'text/html');
    return Array.from(documentFragment.querySelectorAll('img')).some((image) => {
        const source = image.getAttribute('src') || '';
        return source.startsWith('blob:') || source.startsWith('data:');
    });
}
function setArticleContentError(message = '') {
    const error = document.querySelector('[data-article-content-error]');
    const textarea = document.querySelector('#content');
    if (!error || !textarea) return;
    error.textContent = message || 'Article content is required before saving.';
    error.hidden = !message;
    textarea.setAttribute('aria-invalid', message ? 'true' : 'false');
}
function setArticleSubmitState(isSubmitting, message = 'Save Article') {
    const button = document.querySelector('[data-article-submit]');
    const form = document.querySelector('#content')?.closest('form');

    if (form) {
        form.setAttribute('aria-busy', isSubmitting ? 'true' : 'false');
    }

    if (!button) return;

    if (!button.dataset.defaultLabel) {
        button.dataset.defaultLabel = button.textContent.trim() || 'Save Article';
    }

    button.disabled = isSubmitting;
    button.textContent = isSubmitting ? message : button.dataset.defaultLabel;
}
if (window.tinymce) {
    tinymce.init({
        selector:'#content',
        height:460,
        menubar:false,
        plugins:'advlist autolink autoresize code fullscreen help image link lists media preview quickbars searchreplace table visualblocks wordcount',
        toolbar:'undo redo | blocks | bold italic | bullist numlist | link image media table | searchreplace visualblocks fullscreen preview | removeformat code help',
        automatic_uploads:true,
        paste_data_images:true,
        image_title:true,
        images_file_types:'jpeg,jpg,png,webp',
        file_picker_types:'image',
        file_picker_callback(callback, value, meta) {
            if (meta.filetype !== 'image') return;

            const input = document.createElement('input');
            input.type = 'file';
            input.accept = 'image/jpeg,image/png,image/webp';

            input.addEventListener('change', () => {
                const file = input.files?.[0];
                if (!file) return;

                const allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
                const maxSize = 4 * 1024 * 1024;

                if (!allowedTypes.includes(file.type)) {
                    setArticleContentError('Inline images must be JPG, PNG or WebP.');
                    return;
                }

                if (file.size > maxSize) {
                    setArticleContentError('Inline images must be 4MB or smaller. Please compress the image and try again.');
                    return;
                }

                const reader = new FileReader();

                reader.addEventListener('load', () => {
                    const editor = window.tinymce?.get('content');
                    const blobCache = editor?.editorUpload?.blobCache;
                    const result = String(reader.result || '');
                    const base64 = result.split(',')[1];

                    if (!editor || !blobCache || !base64) {
                        setArticleContentError('Image could not be prepared for upload. Please try again.');
                        return;
                    }

                    const id = 'article-inline-' + Date.now() + '-' + Math.random().toString(16).slice(2);
                    const blobInfo = blobCache.create(id, file, base64);
                    blobCache.add(blobInfo);

                    callback(blobInfo.blobUri(), {
                        title: file.name,
                        alt: file.name.replace(/\.[^.]+$/, '').replace(/[-_]+/g, ' '),
                    });

                    setArticleContentError('');
                    updateArticleWordCount();
                });

                reader.readAsDataURL(file);
            });

            input.click();
        },
        images_upload_handler(blobInfo, progress) {
            return new Promise((resolve, reject) => {
                const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                const formData = new FormData();
                formData.append('file', blobInfo.blob(), blobInfo.filename());

                const xhr = new XMLHttpRequest();
                xhr.open('POST', '{{ route('admin.articles.inline-image') }}');
                xhr.setRequestHeader('X-CSRF-TOKEN', token || '');
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                xhr.upload.onprogress = (event) => {
                    if (event.lengthComputable) {
                        progress(Math.round((event.loaded / event.total) * 100));
                    }
                };
                xhr.onload = () => {
                    let json;
                    try {
                        json = JSON.parse(xhr.responseText || '{}');
                    } catch (error) {
                        reject('Upload failed: invalid server response.');
                        return;
                    }

                    if (xhr.status < 200 || xhr.status >= 300 || !json.location) {
                        const message = json.message || json.errors?.file?.[0] || 'Image upload failed. Use JPG, PNG or WebP under 4MB.';
                        reject(message);
                        return;
                    }

                    resolve(json.location);
                };
                xhr.onerror = () => reject('Image upload failed. Check your connection and try again.');
                xhr.send(formData);
            });
        },
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
let articleFormSubmitting = false;
document.querySelector('#content')?.closest('form')?.addEventListener('submit', async (event) => {
    if (articleFormSubmitting) return;

    event.preventDefault();

    syncArticleEditorContent();
    const textarea = document.querySelector('#content');
    const count = articleWordCountFromHtml(textarea?.value || '');
    if (count < 1) {
        setArticleContentError('Article content is required before saving.');
        const editor = window.tinymce?.get('content');
        if (editor) {
            editor.focus();
            editor.getContainer()?.scrollIntoView({ behavior: 'smooth', block: 'center' });
        } else {
            textarea?.focus();
        }
        setArticleSubmitState(false);
    } else {
        setArticleContentError('');
    }

    if (count < 1) return;

    const form = event.currentTarget;
    const editor = window.tinymce?.get('content');

    try {
        setArticleSubmitState(true, 'Uploading images...');

        if (editor && typeof editor.uploadImages === 'function') {
            await editor.uploadImages();
            editor.save();
        } else {
            syncArticleEditorContent();
        }

        if (articleContentHasPendingImages(textarea?.value || '')) {
            throw 'One or more article images are still processing. Please wait a moment, then save again.';
        }

        setArticleSubmitState(true, 'Saving article...');
        articleFormSubmitting = true;
        form.submit();
    } catch (error) {
        articleFormSubmitting = false;
        setArticleSubmitState(false);
        setArticleContentError(typeof error === 'string' ? error : 'One or more article images failed to upload. Please try again before saving.');

        if (editor) {
            editor.focus();
            editor.getContainer()?.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }
});
setTimeout(updateArticleWordCount, 800);
</script>
@endpush
