@csrf
@if($branch->exists) @method('PUT') @endif
<div class="form-grid">
    <div class="form-field"><label>Name</label><input name="name" value="{{ old('name',$branch->name) }}" required></div>
    <div class="form-field"><label>Country</label><input name="country" value="{{ old('country',$branch->country) }}" required></div>
    <div class="form-field"><label>State / City</label><input name="state_city" value="{{ old('state_city',$branch->state_city) }}"></div>
    <div class="form-field"><label>Address</label><input name="address" value="{{ old('address',$branch->address) }}"></div>
    <div class="form-field"><label>Phone</label><input name="phone" value="{{ old('phone',$branch->phone) }}"></div>
    <div class="form-field"><label>Email</label><input type="email" name="email" value="{{ old('email',$branch->email) }}"></div>
    <div class="form-field"><label>Manager Name</label><input name="manager_name" value="{{ old('manager_name',$branch->manager_name) }}"></div>
    <div class="form-field"><label>Status</label><select name="status"><option value="active" @selected(old('status',$branch->status ?: 'active')==='active')>Active</option><option value="inactive" @selected(old('status',$branch->status ?: 'active')==='inactive')>Inactive</option></select></div>
    <div class="form-field full"><label>Service Coverage</label><textarea name="service_coverage" rows="4">{{ old('service_coverage',$branch->service_coverage) }}</textarea></div>
    <div class="form-field"><label>Sort Order</label><input type="number" min="0" name="sort_order" value="{{ old('sort_order',$branch->sort_order ?? 0) }}"></div>
    <label style="display:flex;align-items:center;gap:8px;margin-top:26px"><input type="checkbox" name="is_published" value="1" @checked(old('is_published',$branch->is_published ?? true)) style="width:auto"> Publish branch</label>
</div>
<button class="btn btn-primary" style="margin-top:20px">Save Branch</button>
