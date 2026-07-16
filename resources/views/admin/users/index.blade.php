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
            <td><strong>{{ $user->name }}</strong><br><span class="meta">{{ $user->email }}</span></td>
            <td><span class="badge">{{ $user->roleLabel() }}</span></td>
            <td>{{ $user->region ?: '—' }}</td>
            <td>{{ $user->is_active ? 'Yes' : 'No' }}</td>
            <td class="admin-actions">
                <a class="btn btn-white" href="{{ route('admin.users.edit',$user) }}">Edit</a>
                @if(!$user->is(auth()->user()))
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
        <tr><td><span class="badge">Technical Super Admin</span></td><td>Full access to dashboard, operations, content, users, password resets, settings, SEO, analytics, developer credit and recovery controls.</td></tr>
        <tr><td><span class="badge">Company Owner</span></td><td>Business content, operations, media and visitor analytics. No users, settings, SEO or developer credit controls.</td></tr>
        <tr><td><span class="badge">Content Editor</span></td><td>Articles/resources, media, videos and reviews.</td></tr>
        <tr><td><span class="badge">Service Manager</span></td><td>Services, enquiries, projects, equipment and service-related videos.</td></tr>
        <tr><td><span class="badge">Country Admin</span></td><td>Branches, regional enquiries, local projects and team/branch content.</td></tr>
        <tr><td><span class="badge">Support Agent</span></td><td>Enquiries, client requests and reviews moderation.</td></tr>
        <tr><td><span class="badge">Safety Officer</span></td><td>Safety content and safety-related videos.</td></tr>
    </tbody></table>
</div>
@endsection
