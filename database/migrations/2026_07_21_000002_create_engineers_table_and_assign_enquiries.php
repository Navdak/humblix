<?php

use App\Support\AdminPermissions;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('engineers')) {
            Schema::create('engineers', function (Blueprint $table): void {
                $table->id();
                $table->string('name', 140);
                $table->string('title', 140)->nullable();
                $table->string('field_of_work', 80)->index();
                $table->string('phone', 80)->nullable();
                $table->string('whatsapp', 80)->nullable();
                $table->string('email', 160)->nullable()->index();
                $table->string('region', 140)->nullable();
                $table->string('availability_status', 40)->default('active')->index();
                $table->text('notes')->nullable();
                $table->string('photo_path')->nullable();
                $table->foreignId('linked_user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->unsignedInteger('sort_order')->default(10);
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (Schema::hasTable('enquiries') && ! Schema::hasColumn('enquiries', 'assigned_engineer_id')) {
            Schema::table('enquiries', function (Blueprint $table): void {
                $table->foreignId('assigned_engineer_id')
                    ->nullable()
                    ->after('assigned_to')
                    ->constrained('engineers')
                    ->nullOnDelete();
            });
        }

        if (Schema::hasTable('admin_role_permissions')) {
            $now = now();

            foreach ([
                'company_owner' => ['engineers', 'assign_engineers'],
                'service_manager' => ['engineers', 'assign_engineers'],
                'country_admin' => ['assign_engineers'],
                'support_agent' => ['assign_engineers'],
                'safety_officer' => ['assign_engineers'],
            ] as $role => $permissions) {
                foreach ($permissions as $permission) {
                    DB::table('admin_role_permissions')->updateOrInsert(
                        ['role' => $role, 'permission' => AdminPermissions::normalize($permission)],
                        ['created_at' => $now, 'updated_at' => $now],
                    );
                }
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('enquiries') && Schema::hasColumn('enquiries', 'assigned_engineer_id')) {
            Schema::table('enquiries', function (Blueprint $table): void {
                $table->dropConstrainedForeignId('assigned_engineer_id');
            });
        }

        Schema::dropIfExists('engineers');

        if (Schema::hasTable('admin_role_permissions')) {
            DB::table('admin_role_permissions')
                ->whereIn('role', ['company_owner', 'service_manager'])
                ->whereIn('permission', ['engineers', 'assign_engineers'])
                ->delete();

            DB::table('admin_role_permissions')
                ->whereIn('role', ['country_admin', 'support_agent', 'safety_officer'])
                ->where('permission', 'assign_engineers')
                ->delete();
        }
    }
};
