<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminPermissions
{
    public const MODULES = [
        'dashboard' => 'Dashboard',
        'analytics' => 'Visitor Analytics',
        'enquiries' => 'Enquiries',
        'engineers' => 'Engineers',
        'assign_engineers' => 'Assign Engineers',
        'projects' => 'Projects',
        'branches' => 'Branches',
        'services' => 'Services',
        'equipment' => 'Equipment',
        'videos' => 'Videos',
        'articles' => 'Resources / Articles',
        'newsletter' => 'Newsletter Subscribers',
        'media' => 'Media Library',
        'reviews' => 'Reviews',
        'safety' => 'Safety',
        'team' => 'Team',
        'jobs' => 'Careers',
        'page_heroes' => 'Page Heroes',
        'seo' => 'SEO Settings',
        'settings' => 'Site Settings',
        'users' => 'Users & Roles',
        'developer_profile' => 'Developer Profile',
    ];

    public const DEFAULT_ROLE_PERMISSIONS = [
        'company_owner' => [
            'dashboard',
            'analytics',
            'enquiries',
            'engineers',
            'assign_engineers',
            'projects',
            'branches',
            'services',
            'equipment',
            'videos',
            'team',
            'jobs',
            'articles',
            'newsletter',
            'media',
            'reviews',
            'safety',
            'page_heroes',
            'users',
        ],
        'content_editor' => [
            'dashboard',
            'articles',
            'newsletter',
            'media',
            'videos',
            'reviews',
        ],
        'service_manager' => [
            'dashboard',
            'services',
            'enquiries',
            'engineers',
            'assign_engineers',
            'projects',
            'equipment',
            'videos',
        ],
        'country_admin' => [
            'dashboard',
            'branches',
            'enquiries',
            'assign_engineers',
            'projects',
            'team',
        ],
        'support_agent' => [
            'dashboard',
            'enquiries',
            'assign_engineers',
            'reviews',
        ],
        'safety_officer' => [
            'dashboard',
            'safety',
            'assign_engineers',
            'videos',
        ],
    ];

    public static function normalize(string $permission): string
    {
        return match ($permission) {
            'careers' => 'jobs',
            'resources' => 'articles',
            'seo-settings', 'seo_settings' => 'seo',
            'page-heroes', 'pageheroes' => 'page_heroes',
            'developer-profile', 'developerprofile' => 'developer_profile',
            'newsletter-subscribers', 'newsletter_subscribers' => 'newsletter',
            'assign-engineers', 'assignengineers' => 'assign_engineers',
            'field-engineers', 'field_engineers' => 'engineers',
            default => $permission,
        };
    }

    public static function label(string $permission): string
    {
        $permission = self::normalize($permission);

        return self::MODULES[$permission] ?? ucwords(str_replace('_', ' ', $permission));
    }

    public static function defaultForRole(string $role): array
    {
        $role = $role === 'service_admin' ? 'service_manager' : $role;

        return self::DEFAULT_ROLE_PERMISSIONS[$role] ?? [];
    }

    public static function syncDefaultRolePermissions(): void
    {
        if (! Schema::hasTable('admin_role_permissions')) {
            return;
        }

        $now = now();

        foreach (self::DEFAULT_ROLE_PERMISSIONS as $role => $permissions) {
            foreach ($permissions as $permission) {
                DB::table('admin_role_permissions')->updateOrInsert(
                    ['role' => $role, 'permission' => self::normalize($permission)],
                    ['created_at' => $now, 'updated_at' => $now],
                );
            }
        }
    }
}
