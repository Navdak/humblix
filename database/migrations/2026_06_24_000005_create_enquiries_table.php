<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('enquiries', function (Blueprint $table) {
            $table->id(); $table->string('source')->default('website'); $table->string('name'); $table->string('phone'); $table->string('email')->nullable();
            $table->string('location')->nullable(); $table->string('building_type')->nullable(); $table->string('service_needed'); $table->string('urgency')->nullable();
            $table->text('message')->nullable(); $table->string('attachment_path')->nullable(); $table->string('assigned_to')->nullable();
            $table->string('status')->default('new')->index(); $table->text('notes')->nullable(); $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('enquiries'); }
};
