<?php

use App\Support\AdminPermissions;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('admin_role_permissions')) {
            return;
        }

        $now = now();

        foreach (['company_owner'] as $role) {
            DB::table('admin_role_permissions')->updateOrInsert(
                ['role' => $role, 'permission' => AdminPermissions::normalize('newsletter')],
                ['created_at' => $now, 'updated_at' => $now],
            );
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('admin_role_permissions')) {
            return;
        }

        DB::table('admin_role_permissions')
            ->whereIn('role', ['company_owner'])
            ->where('permission', AdminPermissions::normalize('newsletter'))
            ->delete();
    }
};
