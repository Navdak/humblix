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
        if (! Schema::hasTable('client_jobs')) {
            Schema::create('client_jobs', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('enquiry_id')->unique()->constrained('enquiries')->cascadeOnDelete();
                $table->foreignId('assigned_engineer_id')->nullable()->constrained('engineers')->nullOnDelete();
                $table->string('job_reference', 80)->unique();
                $table->string('status', 60)->default('confirmed')->index();
                $table->string('portal_token', 100)->unique();
                $table->boolean('portal_enabled')->default(true)->index();
                $table->unsignedInteger('admin_unread_count')->default(0);
                $table->unsignedInteger('client_unread_count')->default(0);
                $table->timestamp('last_client_message_at')->nullable();
                $table->timestamp('last_admin_message_at')->nullable();
                $table->decimal('agreed_amount', 15, 2)->nullable();
                $table->string('currency', 10)->default('NGN');
                $table->string('payment_status', 60)->default('pending')->index();
                $table->text('agreement_note')->nullable();
                $table->date('agreed_at')->nullable();
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('job_messages')) {
            Schema::create('job_messages', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('client_job_id')->constrained('client_jobs')->cascadeOnDelete();
                $table->string('sender_type', 40)->index();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('sender_name', 160)->nullable();
                $table->text('body');
                $table->string('visibility', 40)->default('client')->index();
                $table->timestamp('read_by_admin_at')->nullable();
                $table->timestamp('read_by_client_at')->nullable();
                $table->timestamps();
            });
        }

        if (Schema::hasTable('admin_role_permissions')) {
            $now = now();

            foreach (['client_jobs', 'commercial_agreements'] as $permission) {
                DB::table('admin_role_permissions')->updateOrInsert(
                    ['role' => 'company_owner', 'permission' => AdminPermissions::normalize($permission)],
                    ['created_at' => $now, 'updated_at' => $now],
                );
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('job_messages');
        Schema::dropIfExists('client_jobs');

        if (Schema::hasTable('admin_role_permissions')) {
            DB::table('admin_role_permissions')
                ->where('role', 'company_owner')
                ->whereIn('permission', ['client_jobs', 'commercial_agreements'])
                ->delete();
        }
    }
};
