@extends('layouts.admin')
@section('title','Users & Roles')
@section('page_title','Users & Roles')
@section('page_subtitle','Manage administrative access, roles, regions, and account status.')
@section('page_actions')<a class="btn btn-primary" href="{{ route('admin.users.create') }}"><x-admin-icon name="plus"/> New User</a>@endsection
@section('content')
<div class="admin-card">
    <div class="admin-list-intro"><strong>Access directory</strong><span>Protected developer access, company ownership and module-based admin roles.</span></div>
    <table class="admin-table"><thead><tr><th>User</th><th>Role</th><th>Region</th><th>Active</th><th></th></tr></thead><tbody>
    @forelse($users as $user)
        <tr>
            <td data-label="User">
                <strong>{{ $user->name }}</strong><br>
                <span class="meta">{{ $user->email }}</span>
                @if($user->isProtected())<br><span class="badge">Protected developer recovery</span>@endif
            </td>
            <td data-label="Role"><span class="badge">{{ $user->roleLabel() }}</span></td>
            <td data-label="Region">{{ $user->region ?: '—' }}</td>
            <td data-label="Active">{{ $user->is_active ? 'Yes' : 'No' }}</td>
            <td data-label="Actions" class="admin-actions">
                <a class="btn btn-white" href="{{ route('admin.users.edit',$user) }}">Edit</a>
                @if(!$user->is(auth()->user()) && !$user->isProtected())
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
    <div class="admin-list-intro"><strong>Admin role matrix</strong><span>Technical controls remain above normal business/content administration.</span></div>
    <table class="admin-table"><thead><tr><th>Role</th><th>Primary modules</th></tr></thead><tbody>
        <tr><td data-label="Role"><span class="badge">Technical Super Admin</span></td><td data-label="Primary modules">Full access to dashboard, operations, content, users, password resets, settings, SEO, analytics, developer credit and recovery controls. The protected developer recovery account cannot be deleted, demoted, deactivated or modified by another admin.</td></tr>
        <tr><td data-label="Role"><span class="badge">Company Owner</span></td><td data-label="Primary modules">Business content, operations, media and visitor analytics. No users, settings, SEO or developer credit controls.</td></tr>
        <tr><td data-label="Role"><span class="badge">Content Editor</span></td><td data-label="Primary modules">Articles/resources, media, videos and reviews.</td></tr>
        <tr><td data-label="Role"><span class="badge">Service Manager</span></td><td data-label="Primary modules">Services, enquiries, projects, equipment and service-related videos.</td></tr>
        <tr><td data-label="Role"><span class="badge">Country Admin</span></td><td data-label="Primary modules">Branches, regional enquiries, local projects and team/branch content.</td></tr>
        <tr><td data-label="Role"><span class="badge">Support Agent</span></td><td data-label="Primary modules">Enquiries, client requests and reviews moderation.</td></tr>
        <tr><td data-label="Role"><span class="badge">Safety Officer</span></td><td data-label="Primary modules">Safety content and safety-related videos.</td></tr>
    </tbody></table>
</div>
@endsection
