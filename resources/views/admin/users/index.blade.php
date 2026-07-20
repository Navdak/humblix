@extends('layouts.admin')
@section('title','Users & Roles')
@section('page_title','Users & Roles')
@section('page_subtitle','Manage administrative access, roles, regions, and account status.')
@section('page_actions')
<div class="admin-actions">
    @if($canManageRolePermissions ?? false)<a class="btn btn-outline" href="{{ route('admin.roles.index') }}">Role Permissions</a>@endif
    <a class="btn btn-primary" href="{{ route('admin.users.create') }}"><x-admin-icon name="plus"/> New User</a>
</div>
@endsection
@section('content')
<div class="admin-card">
    <div class="admin-list-intro"><strong>Access directory</strong><span>Protected developer access, company ownership and module-based admin roles.</span></div>
    <table class="admin-table"><thead><tr><th>User</th><th>Role</th><th>Region</th><th>Active</th><th></th></tr></thead><tbody>
    @forelse($users as $user)
        <tr>
            <td data-label="User">
                <div class="admin-user-cell">
                    @include('admin.partials.avatar',['user'=>$user])
                    <div>
                        <strong>{{ $user->name }}</strong><br>
                        <span class="meta">{{ $user->email }}</span>
                        @if($user->isProtected())<br><span class="badge">Protected developer recovery</span>@endif
                    </div>
                </div>
            </td>
            <td data-label="Role"><span class="badge">{{ $user->roleLabel() }}</span></td>
            <td data-label="Region">{{ $user->region ?: '—' }}</td>
            <td data-label="Active">{{ $user->is_active ? 'Yes' : 'No' }}</td>
            <td data-label="Actions" class="admin-actions">
                @php
                    $canManageListedUser = auth()->user()?->isSuperAdmin()
                        || (auth()->user()?->hasRole('company_owner') && in_array($user->normalizedRole(), ['content_editor','service_manager','country_admin','support_agent','safety_officer'], true) && ! $user->isProtected());
                @endphp
                @if($canManageListedUser)
                    <a class="btn btn-white" href="{{ route('admin.users.edit',$user) }}">Edit</a>
                @endif
                @if($canManageListedUser && !$user->is(auth()->user()) && !$user->isProtected())
                    <form method="POST" action="{{ route('admin.users.destroy',$user) }}" onsubmit="return confirm('Delete this user?')">@csrf @method('DELETE')<button class="btn btn-outline" style="color:#b91c1c">Delete</button></form>
                @endif
            </td>
        </tr>
    @empty
        <tr><td colspan="5">No users yet.</td></tr>
    @endforelse
    </tbody></table>
    <div style="margin-top:18px">{{ $users->links() }}</div>
</div>

<div class="admin-card" style="margin-top:14px">
    <div class="admin-list-intro">
        <strong>Admin role matrix</strong>
        <span>These permissions control sidebar visibility and direct route access.</span>
    </div>
    <table class="admin-table"><thead><tr><th>Role</th><th>Primary modules</th></tr></thead><tbody>
        @foreach($roles as $role)
            <tr>
                <td data-label="Role">
                    <span class="badge">{{ $roleLabels[$role] ?? ucwords(str_replace('_',' ', $role)) }}</span>
                    @if($role === 'super_admin')<br><small class="meta">Always full access</small>@endif
                </td>
                <td data-label="Primary modules">
                    @foreach(($rolePermissions[$role] ?? []) as $permission)
                        <span class="badge" style="margin:2px">{{ $permissionLabels[$permission] ?? \App\Support\AdminPermissions::label($permission) }}</span>
                    @endforeach
                    @if($role === 'super_admin')
                        <p class="meta" style="margin-top:8px">The protected developer recovery account cannot be deleted, demoted, deactivated or modified by another admin.</p>
                    @endif
                    @if(($canManageRolePermissions ?? false) && $role !== 'super_admin')
                        <div style="margin-top:10px"><a class="admin-row-action" href="{{ route('admin.roles.edit', $role) }}">Edit role permissions</a></div>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody></table>
</div>
@endsection
