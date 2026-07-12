@csrf
@if($article->exists) @method('PUT') @endif
<div class="form-grid" x-data="articleLinks({ initial: @js(old('links', $article->exists ? $article->relatedLinks->map(fn($link) => ['link_text' => $link->link_text, 'url' => $link->url])->values()->toArray() : [])) })">
    <div class="form-field"><label>Title</label><input name="title" value="{{ old('title',$article->title) }}" required></div>
    <div class="form-field"><label>Slug</label><input name="slug" value="{{ old('slug',$article->slug) }}" placeholder="auto-generated if empty"></div>
    <div class="form-field full"><label>SEO Excerpt <span class="meta">max 160 chars</span></label><textarea name="excerpt" rows="3" maxlength="160" required>{{ old('excerpt',$article->excerpt) }}</textarea></div>
    <div class="form-field"><label>Featured Image</label><input type="file" name="featured_image" accept="image/*"></div>
    <div class="form-field"><label>Status</label><select name="status"><option value="draft" @selected(old('status',$article->status ?: 'draft')==='draft')>Draft</option><option value="published" @selected(old('status',$article->status ?: 'draft')==='published')>Published</option></select></div>
    <div class="form-field full"><label>Content</label><textarea id="content" name="content" rows="16" required>{{ old('content',$article->content) }}</textarea></div>
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
<button class="btn btn-primary" style="margin-top:20px">Save Article</button>
@push('head')
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
@endpush
@push('scripts')
<script>
function articleLinks({initial}) { return { links: initial.length ? initial : [], add(){ this.links.push({link_text:'', url:''}) }, remove(i){ this.links.splice(i,1) } } }
if (window.tinymce) {
    tinymce.init({ selector:'#content', height:460, menubar:false, plugins:'lists link table code paste', toolbar:'undo redo | blocks | bold italic | bullist numlist | link table | removeformat code', paste_as_text:false });
}
</script>
@endpush
