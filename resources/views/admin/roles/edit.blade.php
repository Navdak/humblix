@extends('layouts.admin')
@section('title','Edit Role Permissions')
@section('page_title','Edit Role Permissions')
@section('page_subtitle','Choose the admin modules available to '.$roleLabel.'.')
@section('page_actions')<a class="btn btn-outline" href="{{ route('admin.roles.index') }}">Back to Roles</a>@endsection
@section('content')
<form class="admin-card" method="POST" action="{{ route('admin.roles.update', $role) }}">
    @csrf @method('PUT')
    <div class="admin-list-intro">
        <strong>{{ $roleLabel }}</strong>
        <span>{{ $role }}</span>
    </div>
    <div class="admin-note" style="margin-bottom:18px">
        Dashboard access is always enabled. Technical controls remain locked to Technical Super Admin only.
    </div>
    <div class="form-grid">
        @foreach($permissionLabels as $permission => $label)
            @php
                $isAlwaysEnabled = in_array($permission, $alwaysEnabled, true);
                $isTechnicalOnly = in_array($permission, $technicalOnly, true);
                $isEditable = in_array($permission, $editablePermissions, true);
            @endphp
            <label class="admin-note" style="display:flex;align-items:flex-start;gap:10px;margin:0;background:{{ $isTechnicalOnly ? '#fff7ed' : '#f7fbff' }}">
                <input
                    type="checkbox"
                    name="permissions[]"
                    value="{{ $permission }}"
                    @checked($isAlwaysEnabled || in_array($permission, $enabledPermissions, true))
                    @disabled(! $isEditable)
                    style="width:auto;margin-top:3px"
                >
                <span>
                    <strong>{{ $label }}</strong>
                    <br>
                    <small>
                        @if($isAlwaysEnabled)
                            Always enabled for every active admin role.
                        @elseif($isTechnicalOnly)
                            Locked to Technical Super Admin for production safety.
                        @else
                            Allow this role to see the sidebar link and access the module route.
                        @endif
                    </small>
                </span>
            </label>
        @endforeach
    </div>
    <div class="admin-actions" style="margin-top:22px">
        <button class="btn btn-primary" type="submit">Save Permissions</button>
        <a class="btn btn-outline" href="{{ route('admin.roles.index') }}">Cancel</a>
    </div>
</form>
@endsection
