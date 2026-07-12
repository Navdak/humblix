@csrf
@if($member->exists) @method('PUT') @endif
<div class="form-grid">
    <div class="form-field"><label>Name</label><input name="name" value="{{ old('name',$member->name) }}" required></div>
    <div class="form-field"><label>Role</label><input name="role" value="{{ old('role',$member->role) }}" required></div>
    <div class="form-field"><label>Region</label><input name="region" value="{{ old('region',$member->region) }}"></div>
    <div class="form-field"><label>Experience</label><input name="experience" value="{{ old('experience',$member->experience) }}"></div>
    <div class="form-field full"><label>Certifications</label><input name="certifications" value="{{ old('certifications',$member->certifications) }}"></div>
    <div class="form-field full"><label>Bio</label><textarea name="bio" rows="5">{{ old('bio',$member->bio) }}</textarea></div>
    <div class="form-field"><label>Email</label><input type="email" name="email" value="{{ old('email',$member->email) }}"></div>
    <div class="form-field"><label>Phone</label><input name="phone" value="{{ old('phone',$member->phone) }}"></div>
    <div class="form-field full"><label>Social URL</label><input type="url" name="social_url" value="{{ old('social_url',$member->social_url) }}"></div>
    <div class="form-field"><label>Photo</label><input type="file" name="photo" accept="image/*"></div>
    <div class="form-field"><label>Sort Order</label><input type="number" name="sort_order" value="{{ old('sort_order',$member->sort_order ?: 10) }}"></div>
    <label style="display:flex;align-items:center;gap:8px;margin-top:26px"><input type="checkbox" name="is_visible" value="1" @checked(old('is_visible',$member->exists ? $member->is_visible : true)) style="width:auto"> Visible on public team page</label>
</div>
<button class="btn btn-primary" style="margin-top:20px">Save Member</button>
