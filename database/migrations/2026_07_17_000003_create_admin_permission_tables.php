<?php

use App\Support\AdminPermissions;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin_role_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('role', 80);
            $table->string('permission', 120);
            $table->timestamps();
            $table->unique(['role', 'permission']);
            $table->index('role');
        });

        Schema::create('admin_user_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('permission', 120);
            $table->boolean('allowed')->default(true);
            $table->timestamps();
            $table->unique(['user_id', 'permission']);
        });

        AdminPermissions::syncDefaultRolePermissions();
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_user_permissions');
        Schema::dropIfExists('admin_role_permissions');
    }
};
