@extends('layouts.admin')
@section('title','Role Permissions')
@section('page_title','Role Permissions')
@section('page_subtitle','Control which admin modules each role can see and access.')
@section('page_actions')<a class="btn btn-outline" href="{{ route('admin.users.index') }}">Back to Users</a>@endsection
@section('content')
<div class="admin-card">
    <div class="admin-list-intro">
        <strong>Permission control centre</strong>
        <span>Only Technical Super Admin can edit role permissions. Direct URL access is blocked too.</span>
    </div>
    <div class="admin-note" style="margin-bottom:16px">
        <strong>Locked technical controls:</strong> Site Settings, SEO Settings, Users & Roles and Developer Profile remain Technical Super Admin-only. Dashboard is always enabled for every active admin role.
    </div>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Role</th>
                    <th>Current Access</th>
                    <th>Locked</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($roles as $role)
                    @php($permissions = $rolePermissions[$role] ?? [])
                    <tr>
                        <td data-label="Role">
                            <strong>{{ $roleLabels[$role] ?? ucwords(str_replace('_',' ', $role)) }}</strong>
                            <span class="meta">{{ $role }}</span>
                        </td>
                        <td data-label="Current Access">
                            @forelse($permissions as $permission)
                                <span class="badge" style="margin:2px">{{ $permissionLabels[$permission] ?? \App\Support\AdminPermissions::label($permission) }}</span>
                            @empty
                                <span class="meta">No modules enabled</span>
                            @endforelse
                        </td>
                        <td data-label="Locked">
                            @foreach($alwaysEnabled as $permission)
                                <span class="badge" style="margin:2px">{{ $permissionLabels[$permission] ?? $permission }}</span>
                            @endforeach
                        </td>
                        <td data-label="Actions" class="admin-actions">
                            <a class="btn btn-white" href="{{ route('admin.roles.edit', $role) }}">Edit Permissions</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="admin-card" style="margin-top:14px">
    <div class="admin-list-intro">
        <strong>Technical Super Admin</strong>
        <span>Protected developer access</span>
    </div>
    <p class="section-sub" style="margin:0">The Technical Super Admin role is intentionally not editable here because it always has full platform, security, recovery, SEO, settings, users and developer-profile access.</p>
</div>
@endsection
