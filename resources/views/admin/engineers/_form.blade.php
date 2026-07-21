@csrf
@if($engineer->exists) @method('PUT') @endif

<div class="form-grid">
    <div class="form-field">
        <label>Name</label>
        <input name="name" value="{{ old('name', $engineer->name) }}" required placeholder="Engineer full name">
    </div>
    <div class="form-field">
        <label>Role / Title</label>
        <input name="title" value="{{ old('title', $engineer->title) }}" placeholder="Senior HVAC Engineer, Solar Technician...">
    </div>
    <div class="form-field">
        <label>Field of Work</label>
        <select name="field_of_work" required>
            <option value="">Choose field</option>
            @foreach(\App\Models\Engineer::FIELDS_OF_WORK as $field)
                <option value="{{ $field }}" @selected(old('field_of_work', $engineer->field_of_work) === $field)>{{ $field }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-field">
        <label>Availability</label>
        <select name="availability_status" required>
            @foreach(\App\Models\Engineer::AVAILABILITY_STATUSES as $value => $label)
                <option value="{{ $value }}" @selected(old('availability_status', $engineer->availability_status ?: 'active') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-field">
        <label>Phone</label>
        <input name="phone" value="{{ old('phone', $engineer->phone) }}" placeholder="+234...">
    </div>
    <div class="form-field">
        <label>WhatsApp</label>
        <input name="whatsapp" value="{{ old('whatsapp', $engineer->whatsapp) }}" placeholder="+234...">
    </div>
    <div class="form-field">
        <label>Email</label>
        <input type="email" name="email" value="{{ old('email', $engineer->email) }}" placeholder="engineer@example.com">
        <small>Used only when an admin chooses to send an assignment alert.</small>
    </div>
    <div class="form-field">
        <label>Region / Location</label>
        <input name="region" value="{{ old('region', $engineer->region) }}" placeholder="Lagos, Abuja, Port Harcourt...">
    </div>
    <div class="form-field">
        <label>Linked Admin User</label>
        <select name="linked_user_id">
            <option value="">No linked admin user</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}" @selected((int) old('linked_user_id', $engineer->linked_user_id) === $user->id)>
                    {{ $user->displayName() }} — {{ $user->roleLabel() }}
                </option>
            @endforeach
        </select>
        <small>Optional. Many engineers will not have admin accounts.</small>
    </div>
    <div class="form-field">
        <label>Sort Order</label>
        <input type="number" name="sort_order" value="{{ old('sort_order', $engineer->sort_order ?: 10) }}" min="0" max="999">
    </div>
    <div class="form-field full">
        <label>Photo</label>
        @if($engineer->exists)
            <div class="admin-profile-preview" style="margin-bottom:12px">
                <img src="{{ $engineer->photoUrl() }}" alt="{{ $engineer->name }}" style="width:78px;height:78px;border-radius:22px;object-fit:cover;border:1px solid var(--line);">
                <div>
                    <strong>Current engineer image</strong>
                    <small>Upload a new image to replace it.</small>
                    @if($engineer->hasUploadedPhoto())
                        <label style="display:flex;align-items:center;gap:8px;margin-top:8px">
                            <input type="checkbox" name="remove_photo" value="1" style="width:auto">
                            Remove uploaded photo and use fallback image
                        </label>
                    @endif
                </div>
            </div>
        @endif
        <input type="file" name="photo" accept="image/*">
    </div>
    <div class="form-field full">
        <label>Internal Notes</label>
        <textarea name="notes" rows="5" placeholder="Internal skill notes, coverage areas, scheduling notes, safety notes...">{{ old('notes', $engineer->notes) }}</textarea>
        <small>Internal only. This does not appear on the public Team page.</small>
    </div>
</div>

<div class="admin-actions" style="margin-top:22px">
    <button class="btn btn-primary" type="submit">Save Engineer</button>
    <a class="btn btn-outline" href="{{ route('admin.engineers.index') }}">Cancel</a>
</div>
