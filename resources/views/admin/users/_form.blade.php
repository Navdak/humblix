@csrf
@if($user->exists) @method('PUT') @endif
<div class="form-grid">
    <div class="form-field"><label>Display Name</label><input name="name" value="{{ old('name',$user->name) }}" required><small>This name appears in the admin welcome greeting.</small></div>
    <div class="form-field"><label>Email</label><input type="email" name="email" value="{{ old('email',$user->email) }}" required @disabled($user->exists && $user->isProtected())>@if($user->exists && $user->isProtected())<input type="hidden" name="email" value="{{ $user->email }}">@endif</div>
    <div class="form-field"><label>Password {{ $user->exists ? '(leave empty to keep current)' : '' }}</label><input type="password" name="password" {{ $user->exists ? '' : 'required' }}></div>
    <div class="form-field"><label>Role</label><select name="role" required @disabled($user->exists && $user->isProtected())>@foreach($roles as $role)<option value="{{ $role }}" @selected(old('role',$user->normalizedRole() ?: 'support_agent')===$role)>{{ $roleLabels[$role] ?? ucwords(str_replace('_',' ', $role)) }}</option>@endforeach</select>@if($user->exists && $user->isProtected())<input type="hidden" name="role" value="super_admin">@endif</div>
    <div class="form-field"><label>Phone</label><input name="phone" value="{{ old('phone',$user->phone) }}"></div>
    <div class="form-field"><label>Region</label><input name="region" value="{{ old('region',$user->region) }}"></div>
    <label style="display:flex;align-items:center;gap:8px;margin-top:26px"><input type="checkbox" name="is_active" value="1" @checked(old('is_active',$user->exists ? $user->is_active : true)) @disabled($user->exists && $user->isProtected()) style="width:auto"> Active account</label>
    @if($user->exists && $user->isProtected())<input type="hidden" name="is_active" value="1">@endif
</div>
<div class="admin-note" style="margin-top:14px">
    <strong>Role foundation:</strong> Technical Super Admin has full developer and recovery access. Company Owner has business/content access. Sensitive settings, SEO, users and developer credit controls remain Technical Super Admin only.
    @if($user->exists && $user->isProtected())<br><strong>Protected account:</strong> This developer recovery account cannot be deleted, deactivated, demoted, reassigned, or edited by another admin.@endif
</div>
<button class="btn btn-primary" style="margin-top:20px">Save User</button>
