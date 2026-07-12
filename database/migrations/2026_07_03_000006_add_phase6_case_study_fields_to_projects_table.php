<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table): void {
            $table->string('country')->nullable()->after('client_type');
            $table->string('service_division')->nullable()->after('sector');
            $table->text('safety_controls')->nullable()->after('equipment_used');
            $table->string('duration')->nullable()->after('safety_controls');
            $table->text('outcome')->nullable()->after('duration');
            $table->text('client_testimonial')->nullable()->after('outcome');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table): void {
            $table->dropColumn(['country', 'service_division', 'safety_controls', 'duration', 'outcome', 'client_testimonial']);
        });
    }
};
