<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public const ROLES = [
        'super_admin',
        'content_editor',
        'service_manager',
        'country_admin',
        'support_agent',
        'safety_officer',
    ];

    public const ROLE_LABELS = [
        'super_admin' => 'Super Admin',
        'content_editor' => 'Content Editor',
        'service_manager' => 'Service Manager',
        'country_admin' => 'Country Admin',
        'support_agent' => 'Support Agent',
        'safety_officer' => 'Safety Officer',
        'service_admin' => 'Service Manager',
    ];

    public const ROLE_PERMISSIONS = [
        'content_editor' => ['dashboard', 'articles', 'media', 'videos', 'reviews'],
        'service_manager' => ['dashboard', 'services', 'enquiries', 'projects', 'equipment', 'videos'],
        'country_admin' => ['dashboard', 'branches', 'enquiries', 'projects', 'team'],
        'support_agent' => ['dashboard', 'enquiries', 'reviews'],
        'safety_officer' => ['dashboard', 'safety', 'videos'],
    ];

    protected $fillable = ['name','email','password','role','phone','region','avatar_path','is_active'];
    protected $hidden = ['password','remember_token'];

    protected function casts(): array
    {
        return ['email_verified_at' => 'datetime', 'password' => 'hashed', 'is_active' => 'boolean'];
    }

    public function isAdmin(): bool
    {
        return (bool) $this->is_active && in_array($this->normalizedRole(), array_merge(self::ROLES, ['service_admin']), true);
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super_admin');
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
        if ($this->isSuperAdmin()) {
            return true;
        }

        $role = $this->normalizedRole();

        return in_array($module, self::ROLE_PERMISSIONS[$role] ?? [], true);
    }

    public function roleLabel(): string
    {
        return self::ROLE_LABELS[$this->role] ?? self::ROLE_LABELS[$this->normalizedRole()] ?? ucwords(str_replace('_', ' ', (string) $this->role));
    }

    public function normalizedRole(): string
    {
        return $this->role === 'service_admin' ? 'service_manager' : (string) $this->role;
    }
}
