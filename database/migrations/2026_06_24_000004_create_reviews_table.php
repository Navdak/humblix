<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id(); $table->string('client_name'); $table->string('client_role')->nullable(); $table->string('company')->nullable();
            $table->string('location')->nullable(); $table->string('project_category')->nullable(); $table->unsignedTinyInteger('rating')->default(5);
            $table->text('comment'); $table->text('admin_response')->nullable(); $table->boolean('is_approved')->default(false)->index(); $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('reviews'); }
};
