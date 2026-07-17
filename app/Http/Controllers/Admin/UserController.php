<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminRolePermission;
use App\Models\User;
use App\Support\AdminPermissions;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $this->authorizeUsersModule();

        return view('admin.users.index', [
            'users' => User::latest()->paginate(15),
            'roles' => User::ROLES,
            'roleLabels' => User::ROLE_LABELS,
            'permissionLabels' => AdminPermissions::MODULES,
            'rolePermissions' => $this->rolePermissionMap(),
        ]);
    }

    public function create()
    {
        $this->authorizeUsersModule();

        return view('admin.users.create', [
            'user' => new User(),
            'roles' => User::ROLES,
            'roleLabels' => User::ROLE_LABELS,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeUsersModule();
        $data = $this->validated($request);
        $this->storeAvatarIfUploaded($request, $data);
        User::create($data);

        return redirect()->route('admin.users.index')->with('success','Admin user created.');
    }

    public function edit(User $user)
    {
        $this->authorizeUsersModule();

        return view('admin.users.edit', [
            'user' => $user,
            'roles' => User::ROLES,
            'roleLabels' => User::ROLE_LABELS,
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $this->authorizeUsersModule();
        $data = $this->validated($request, $user);
        if (blank($data['password'] ?? null)) unset($data['password']);
        $this->protectDeveloperAccount($user, $data);
        $this->preventLastSuperAdminLoss($user, $data);
        $this->updateAvatarIfRequested($request, $user, $data);
        $user->update($data);

        return back()->with('success','Admin user updated.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->authorizeUsersModule();
        abort_if($user->is(auth()->user()), 403, 'You cannot delete your own account.');
        abort_if($user->isProtected(), 403, 'The protected developer recovery account cannot be deleted.');
        abort_if($user->isSuperAdmin() && $this->activeSuperAdminCount() <= 1, 403, 'You cannot delete the last active Super Admin.');
        $this->deleteUploadedAvatar($user);
        $user->delete();

        return back()->with('success','Admin user deleted.');
    }

    private function validated(Request $request, ?User $user = null): array
    {
        return $request->validate([
            'name' => ['required','string','max:120'],
            'email' => ['required','email','max:160', Rule::unique('users','email')->ignore($user?->id)],
            'password' => [$user?->exists ? 'nullable' : 'required','string','min:8','max:120'],
            'role' => ['required', Rule::in(User::ROLES)],
            'phone' => ['nullable','string','max:80'],
            'region' => ['nullable','string','max:120'],
            'is_active' => ['nullable','boolean'],
            'avatar' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:4096'],
            'remove_avatar' => ['nullable','boolean'],
        ]) + ['is_active' => (bool) $request->boolean('is_active')];
    }

    private function authorizeUsersModule(): void
    {
        abort_unless(auth()->user()?->canManage('users'), 403);
    }

    private function preventLastSuperAdminLoss(User $user, array $data): void
    {
        if (! $user->isSuperAdmin() || $this->activeSuperAdminCount() > 1) {
            return;
        }

        abort_if(($data['role'] ?? $user->role) !== 'super_admin', 403, 'You cannot demote the last active Super Admin.');
        abort_if(! (bool) ($data['is_active'] ?? $user->is_active), 403, 'You cannot deactivate the last active Super Admin.');
    }

    private function protectDeveloperAccount(User $user, array &$data): void
    {
        if (! $user->isProtected()) {
            return;
        }

        abort_unless($user->is(auth()->user()), 403, 'Only the protected developer account can update its own profile.');

        $data['email'] = $user->email;
        $data['role'] = 'super_admin';
        $data['is_active'] = true;
        $data['is_protected'] = true;
    }

    private function activeSuperAdminCount(): int
    {
        return User::where('role', 'super_admin')->where('is_active', true)->count();
    }

    private function storeAvatarIfUploaded(Request $request, array &$data): void
    {
        unset($data['avatar'], $data['remove_avatar']);

        if ($request->hasFile('avatar')) {
            $data['avatar_path'] = $request->file('avatar')->store('admin-avatars', 'public');
        }
    }

    private function updateAvatarIfRequested(Request $request, User $user, array &$data): void
    {
        unset($data['avatar'], $data['remove_avatar']);

        if ($request->boolean('remove_avatar')) {
            $this->deleteUploadedAvatar($user);
            $data['avatar_path'] = null;
        }

        if ($request->hasFile('avatar')) {
            $this->deleteUploadedAvatar($user);
            $data['avatar_path'] = $request->file('avatar')->store('admin-avatars', 'public');
        }
    }

    private function deleteUploadedAvatar(User $user): void
    {
        if ($user->hasUploadedAvatar()) {
            Storage::disk('public')->delete($user->avatar_path);
        }
    }

    private function rolePermissionMap(): array
    {
        return collect(User::ROLES)
            ->mapWithKeys(function (string $role): array {
                if ($role === 'super_admin') {
                    return [$role => array_keys(AdminPermissions::MODULES)];
                }

                return [
                    $role => AdminRolePermission::query()
                        ->where('role', $role)
                        ->pluck('permission')
                        ->map(fn (string $permission) => AdminPermissions::normalize($permission))
                        ->unique()
                        ->values()
                        ->all(),
                ];
            })
            ->all();
    }
}
