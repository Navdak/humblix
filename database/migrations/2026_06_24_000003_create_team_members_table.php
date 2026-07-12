<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('team_members', function (Blueprint $table) {
            $table->id(); $table->string('name'); $table->string('role'); $table->string('region')->nullable(); $table->string('experience')->nullable();
            $table->string('certifications')->nullable(); $table->text('bio')->nullable(); $table->string('email')->nullable(); $table->string('phone')->nullable();
            $table->string('social_url')->nullable(); $table->string('photo_path')->nullable(); $table->boolean('is_visible')->default(true)->index();
            $table->unsignedInteger('sort_order')->default(10); $table->timestamps(); $table->softDeletes();
        });
    }
    public function down(): void { Schema::dropIfExists('team_members'); }
};
