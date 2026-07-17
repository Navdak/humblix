<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_protected')->default(false)->after('is_active');
        });

        $protectedEmail = env('HUMELIX_SUPER_ADMIN_EMAIL', env('ADMIN_EMAIL', 'admin@humelix.com'));

        $protectedUser = DB::table('users')
            ->where('email', $protectedEmail)
            ->orWhere('role', 'super_admin')
            ->orderByRaw("CASE WHEN email = ? THEN 0 ELSE 1 END", [$protectedEmail])
            ->orderBy('id')
            ->first();

        if ($protectedUser) {
            DB::table('users')->where('id', $protectedUser->id)->update([
                'role' => 'super_admin',
                'is_active' => true,
                'is_protected' => true,
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_protected');
        });
    }
};
