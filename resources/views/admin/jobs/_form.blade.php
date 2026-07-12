@csrf
@if($job->exists) @method('PUT') @endif
<div class="form-grid">
    <div class="form-field"><label>Title</label><input name="title" value="{{ old('title',$job->title) }}" required></div>
    <div class="form-field"><label>Department</label><input name="department" value="{{ old('department',$job->department) }}"></div>
    <div class="form-field"><label>Location</label><input name="location" value="{{ old('location',$job->location) }}"></div>
    <div class="form-field"><label>Employment Type</label><input name="employment_type" value="{{ old('employment_type',$job->employment_type) }}" placeholder="Full-time, Contract..."></div>
    <div class="form-field"><label>Status</label><select name="status"><option value="draft" @selected(old('status',$job->status ?: 'draft')==='draft')>Draft</option><option value="open" @selected(old('status',$job->status ?: 'draft')==='open')>Open</option><option value="closed" @selected(old('status',$job->status ?: 'draft')==='closed')>Closed</option></select></div>
    <div class="form-field"><label>Published At</label><input type="datetime-local" name="published_at" value="{{ old('published_at', optional($job->published_at)->format('Y-m-d\TH:i')) }}"></div>
    <div class="form-field"><label>Closing Date</label><input type="date" name="closing_date" value="{{ old('closing_date', optional($job->closing_date)->format('Y-m-d')) }}"></div>
    <div class="form-field"><label>Application Email</label><input type="email" name="application_email" value="{{ old('application_email',$job->application_email) }}"></div>
    <div class="form-field full"><label>Application URL</label><input type="url" name="application_url" value="{{ old('application_url',$job->application_url) }}"></div>
    <div class="form-field full"><label>Description</label><textarea name="description" rows="5">{{ old('description',$job->description) }}</textarea></div>
    <div class="form-field full"><label>Requirements</label><textarea name="requirements" rows="5">{{ old('requirements',$job->requirements) }}</textarea></div>
    <div class="form-field"><label>Sort Order</label><input type="number" min="0" name="sort_order" value="{{ old('sort_order',$job->sort_order ?? 0) }}"></div>
</div>
<button class="btn btn-primary" style="margin-top:20px">Save Job Opening</button>
