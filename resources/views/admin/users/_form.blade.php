@csrf
@if($user->exists) @method('PUT') @endif
<div class="form-grid">
    <div class="form-field"><label>Name</label><input name="name" value="{{ old('name',$user->name) }}" required></div>
    <div class="form-field"><label>Email</label><input type="email" name="email" value="{{ old('email',$user->email) }}" required></div>
    <div class="form-field"><label>Password {{ $user->exists ? '(leave empty to keep current)' : '' }}</label><input type="password" name="password" {{ $user->exists ? '' : 'required' }}></div>
    <div class="form-field"><label>Role</label><select name="role" required>@foreach($roles as $role)<option value="{{ $role }}" @selected(old('role',$user->normalizedRole() ?: 'support_agent')===$role)>{{ $roleLabels[$role] ?? ucwords(str_replace('_',' ', $role)) }}</option>@endforeach</select></div>
    <div class="form-field"><label>Phone</label><input name="phone" value="{{ old('phone',$user->phone) }}"></div>
    <div class="form-field"><label>Region</label><input name="region" value="{{ old('region',$user->region) }}"></div>
    <label style="display:flex;align-items:center;gap:8px;margin-top:26px"><input type="checkbox" name="is_active" value="1" @checked(old('is_active',$user->exists ? $user->is_active : true)) style="width:auto"> Active account</label>
</div>
<div class="admin-note" style="margin-top:14px">
    <strong>Role foundation:</strong> Super Admin has full access. Other roles receive module-level access according to the Phase 8 permission matrix and sensitive configuration remains Super Admin only.
</div>
<button class="btn btn-primary" style="margin-top:20px">Save User</button>
