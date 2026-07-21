@csrf
@if($project->exists) @method('PUT') @endif
<div class="form-grid">
    <div class="form-field"><label>Title</label><input name="title" value="{{ old('title',$project->title) }}" required></div>
    <div class="form-field"><label>Slug</label><input name="slug" value="{{ old('slug',$project->slug) }}" placeholder="auto-generated if empty"></div>
    <div class="form-field"><label>Client Type</label><input name="client_type" value="{{ old('client_type',$project->client_type) }}"></div>
    <div class="form-field"><label>Country</label><input name="country" value="{{ old('country',$project->country) }}" placeholder="Nigeria, UK, UAE..."></div>
    <div class="form-field"><label>Location</label><input name="location" value="{{ old('location',$project->location) }}" required></div>
    <div class="form-field"><label>Sector</label><input name="sector" value="{{ old('sector',$project->sector) }}" required placeholder="Factories, Offices, Warehouses..."></div>
    <div class="form-field"><label>Service Division</label><input name="service_division" value="{{ old('service_division',$project->service_division) }}" placeholder="HVAC, Solar, Electrical..."></div>
    <div class="form-field"><label>System Type</label><input name="system_type" value="{{ old('system_type',$project->system_type) }}" required></div>
    <div class="form-field"><label>Duration</label><input name="duration" value="{{ old('duration',$project->duration) }}" placeholder="3 weeks"></div>
    <div class="form-field full">
        <label>Client Challenge</label>
        <textarea name="challenge" rows="3" placeholder="What problem, site condition, capacity issue or operational need did the client have?">{{ old('challenge',$project->challenge) }}</textarea>
        <small>This appears as the first part of the public case study.</small>
    </div>
    <div class="form-field full">
        <label>Solution Delivered</label>
        <textarea name="solution" rows="3" placeholder="Explain what Humelix installed, repaired, supplied, configured or recommended.">{{ old('solution',$project->solution) }}</textarea>
    </div>
    <div class="form-field full">
        <label>Result / Outcome Summary</label>
        <textarea name="result" rows="3" placeholder="State the practical result: improved comfort, safer power, system readiness, reduced downtime, etc.">{{ old('result',$project->result) }}</textarea>
    </div>
    <div class="form-field full">
        <label>Extended Outcome</label>
        <textarea name="outcome" rows="3" placeholder="Optional extra outcome details, handover notes or client impact.">{{ old('outcome',$project->outcome) }}</textarea>
    </div>
    <div class="form-field full">
        <label>Equipment / Materials Used</label>
        <input name="equipment_used" value="{{ old('equipment_used',$project->equipment_used) }}" placeholder="VRF system, solar panels, cables, protection devices, ducts...">
    </div>
    <div class="form-field full">
        <label>Safety Approach / Controls</label>
        <textarea name="safety_controls" rows="3" placeholder="Mention PPE, isolation, access control, site protection, testing, commissioning or handover controls.">{{ old('safety_controls',$project->safety_controls) }}</textarea>
    </div>
    <div class="form-field full"><label>Client Testimonial</label><textarea name="client_testimonial" rows="3">{{ old('client_testimonial',$project->client_testimonial) }}</textarea></div>
    <div class="form-field"><label>Image</label><input type="file" name="image" accept="image/*"></div>
    <div class="form-field"><label>Status</label><select name="status"><option value="draft" @selected(old('status',$project->status ?: 'published')==='draft')>Draft</option><option value="published" @selected(old('status',$project->status ?: 'published')==='published')>Published</option></select></div>
    <label style="display:flex;align-items:center;gap:8px;margin-top:26px"><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured',$project->is_featured)) style="width:auto"> Feature on homepage</label>
</div>
<button class="btn btn-primary" style="margin-top:20px">Save Project</button>
