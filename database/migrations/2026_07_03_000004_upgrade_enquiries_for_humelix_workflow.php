<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('enquiries', function (Blueprint $table): void {
            $table->string('reference_number')->nullable()->unique()->after('id');
            $table->string('company_name')->nullable()->after('name');
            $table->string('country')->nullable()->after('company_name');
            $table->string('state_city')->nullable()->after('country');
            $table->string('project_location')->nullable()->after('location');
            $table->string('type_of_work')->nullable()->after('service_needed');
            $table->string('preferred_contact')->nullable()->after('urgency');
            $table->json('uploaded_files')->nullable()->after('attachment_path');
            $table->timestamp('reviewed_at')->nullable()->after('notes');
            $table->timestamp('contacted_at')->nullable()->after('reviewed_at');
        });
    }

    public function down(): void
    {
        Schema::table('enquiries', function (Blueprint $table): void {
            $table->dropUnique(['reference_number']);
            $table->dropColumn([
                'reference_number',
                'company_name',
                'country',
                'state_city',
                'project_location',
                'type_of_work',
                'preferred_contact',
                'uploaded_files',
                'reviewed_at',
                'contacted_at',
            ]);
        });
    }
};
