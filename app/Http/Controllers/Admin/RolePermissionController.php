<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminRolePermission;
use App\Models\User;
use App\Support\AdminPermissions;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class RolePermissionController extends Controller
{
    private const ALWAYS_ENABLED = ['dashboard'];

    private const TECHNICAL_ONLY = [
        'settings',
        'seo',
        'users',
        'developer_profile',
    ];

    public function index()
    {
        $this->authorizeSuperAdmin();

        return view('admin.roles.index', [
            'roles' => $this->manageableRoles(),
            'roleLabels' => User::ROLE_LABELS,
            'permissionLabels' => AdminPermissions::MODULES,
            'rolePermissions' => $this->rolePermissionMap(),
            'technicalOnly' => self::TECHNICAL_ONLY,
            'alwaysEnabled' => self::ALWAYS_ENABLED,
        ]);
    }

    public function edit(string $role)
    {
        $this->authorizeSuperAdmin();
        $this->abortIfRoleIsNotManageable($role);

        return view('admin.roles.edit', [
            'role' => $role,
            'roleLabel' => User::ROLE_LABELS[$role] ?? ucwords(str_replace('_', ' ', $role)),
            'permissionLabels' => AdminPermissions::MODULES,
            'enabledPermissions' => $this->permissionsForRole($role),
            'technicalOnly' => self::TECHNICAL_ONLY,
            'alwaysEnabled' => self::ALWAYS_ENABLED,
            'editablePermissions' => $this->editablePermissions(),
        ]);
    }

    public function update(Request $request, string $role): RedirectResponse
    {
        $this->authorizeSuperAdmin();
        $this->abortIfRoleIsNotManageable($role);

        $data = $request->validate([
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', Rule::in($this->editablePermissions())],
        ]);

        $permissions = collect($data['permissions'] ?? [])
            ->map(fn (string $permission) => AdminPermissions::normalize($permission))
            ->intersect($this->editablePermissions())
            ->merge(self::ALWAYS_ENABLED)
            ->unique()
            ->values();

        DB::transaction(function () use ($role, $permissions): void {
            AdminRolePermission::query()->where('role', $role)->delete();

            $now = now();
            $permissions->each(fn (string $permission) => AdminRolePermission::query()->create([
                'role' => $role,
                'permission' => $permission,
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        });

        return redirect()->route('admin.roles.index')->with('success', 'Role permissions updated.');
    }

    private function authorizeSuperAdmin(): void
    {
        abort_unless(auth()->user()?->isSuperAdmin(), 403);
    }

    private function manageableRoles(): array
    {
        return array_values(array_filter(User::ROLES, fn (string $role) => $role !== 'super_admin'));
    }

    private function abortIfRoleIsNotManageable(string $role): void
    {
        abort_unless(in_array($role, $this->manageableRoles(), true), 404);
    }

    private function editablePermissions(): array
    {
        return array_values(array_diff(
            array_keys(AdminPermissions::MODULES),
            self::ALWAYS_ENABLED,
            self::TECHNICAL_ONLY,
        ));
    }

    private function rolePermissionMap(): array
    {
        return collect($this->manageableRoles())
            ->mapWithKeys(fn (string $role) => [$role => $this->permissionsForRole($role)])
            ->all();
    }

    private function permissionsForRole(string $role): array
    {
        return AdminRolePermission::query()
            ->where('role', $role)
            ->pluck('permission')
            ->map(fn (string $permission) => AdminPermissions::normalize($permission))
            ->unique()
            ->values()
            ->all();
    }
}
