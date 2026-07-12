<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('projects', function (Blueprint $table) {
            $table->id(); $table->string('title'); $table->string('slug')->unique(); $table->string('client_type')->nullable();
            $table->string('location'); $table->string('sector')->index(); $table->string('system_type');
            $table->text('challenge')->nullable(); $table->text('solution')->nullable(); $table->text('result')->nullable();
            $table->string('equipment_used')->nullable(); $table->string('image_path')->nullable(); $table->json('gallery')->nullable();
            $table->boolean('is_featured')->default(false)->index(); $table->string('status')->default('draft')->index();
            $table->timestamps(); $table->softDeletes();
        });
    }
    public function down(): void { Schema::dropIfExists('projects'); }
};
