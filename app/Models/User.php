<?php
namespace App\Models;

use App\Support\AdminPermissions;
use App\Support\UchContent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Schema;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public const ROLES = [
        'super_admin',
        'company_owner',
        'content_editor',
        'service_manager',
        'country_admin',
        'support_agent',
        'safety_officer',
    ];

    public const ROLE_LABELS = [
        'super_admin' => 'Technical Super Admin',
        'company_owner' => 'Company Owner',
        'content_editor' => 'Content Editor',
        'service_manager' => 'Service Manager',
        'country_admin' => 'Country Admin',
        'support_agent' => 'Support Agent',
        'safety_officer' => 'Safety Officer',
        'service_admin' => 'Service Manager',
    ];

    public const ROLE_PERMISSIONS = AdminPermissions::DEFAULT_ROLE_PERMISSIONS;

    protected $fillable = ['name','email','password','role','phone','region','avatar_path','is_active'];
    protected $hidden = ['password','remember_token'];

    private ?array $resolvedAdminPermissions = null;

    protected function casts(): array
    {
        return ['email_verified_at' => 'datetime', 'password' => 'hashed', 'is_active' => 'boolean', 'is_protected' => 'boolean'];
    }

    public function isAdmin(): bool
    {
        return (bool) $this->is_active && in_array($this->normalizedRole(), array_merge(self::ROLES, ['service_admin']), true);
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super_admin');
    }

    public function isProtected(): bool
    {
        return (bool) $this->is_protected;
    }

    public function canDeleteRecords(): bool
    {
        return $this->isSuperAdmin() || $this->hasRole('company_owner');
    }

    public function hasRole(string $role): bool
    {
        return $this->normalizedRole() === $role;
    }

    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->normalizedRole(), $roles, true);
    }

    public function canManage(string $module): bool
    {
        $module = AdminPermissions::normalize($module);

        if ($this->isSuperAdmin()) {
            return true;
        }

        return in_array($module, $this->adminPermissions(), true);
    }

    public function adminPermissionOverrides(): HasMany
    {
        return $this->hasMany(AdminUserPermission::class);
    }

    public function adminPermissions(): array
    {
        if ($this->resolvedAdminPermissions !== null) {
            return $this->resolvedAdminPermissions;
        }

        $role = $this->normalizedRole();

        if (! $this->permissionsTablesAreReady()) {
            return $this->resolvedAdminPermissions = AdminPermissions::defaultForRole($role);
        }

        $permissions = AdminRolePermission::query()
            ->where('role', $role)
            ->pluck('permission')
            ->map(fn (string $permission) => AdminPermissions::normalize($permission))
            ->values()
            ->all();

        $overrides = $this->adminPermissionOverrides()
            ->get(['permission', 'allowed'])
            ->mapWithKeys(fn (AdminUserPermission $override) => [
                AdminPermissions::normalize($override->permission) => (bool) $override->allowed,
            ]);

        foreach ($overrides as $permission => $allowed) {
            if ($allowed && ! in_array($permission, $permissions, true)) {
                $permissions[] = $permission;
            }

            if (! $allowed) {
                $permissions = array_values(array_filter(
                    $permissions,
                    fn (string $existing) => $existing !== $permission
                ));
            }
        }

        return $this->resolvedAdminPermissions = array_values(array_unique($permissions));
    }

    public function adminPermissionLabels(): array
    {
        return collect($this->adminPermissions())
            ->mapWithKeys(fn (string $permission) => [$permission => AdminPermissions::label($permission)])
            ->all();
    }

    public function roleLabel(): string
    {
        return self::ROLE_LABELS[$this->role] ?? self::ROLE_LABELS[$this->normalizedRole()] ?? ucwords(str_replace('_', ' ', (string) $this->role));
    }

    public function displayName(): string
    {
        return trim((string) $this->name) ?: $this->roleLabel();
    }

    public function avatarInitial(): string
    {
        return strtoupper(substr($this->displayName() ?: 'A', 0, 1));
    }

    public function avatarUrl(): ?string
    {
        return UchContent::imageUrl($this->avatar_path);
    }

    public function hasUploadedAvatar(): bool
    {
        return filled($this->avatar_path) && ! str_starts_with((string) $this->avatar_path, 'images/');
    }

    public function roleSummary(): string
    {
        return match ($this->normalizedRole()) {
            'super_admin' => 'Full system, developer and recovery access',
            'company_owner' => 'Business content, page heroes, operations and visitor analytics',
            'service_manager' => 'Service enquiries, projects, equipment and videos',
            'country_admin' => 'Regional branches, enquiries, projects and team content',
            'content_editor' => 'Resources, media, videos and reviews',
            'safety_officer' => 'Safety content and safety video oversight',
            'support_agent' => 'Client enquiries and review support',
            default => 'Administrative access',
        };
    }

    public function normalizedRole(): string
    {
        return $this->role === 'service_admin' ? 'service_manager' : (string) $this->role;
    }

    private function permissionsTablesAreReady(): bool
    {
        try {
            return Schema::hasTable('admin_role_permissions')
                && Schema::hasTable('admin_user_permissions');
        } catch (\Throwable) {
            return false;
        }
    }
}
