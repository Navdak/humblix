<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('related_links', function (Blueprint $table) {
            $table->id(); $table->foreignId('article_id')->constrained()->cascadeOnDelete(); $table->string('link_text'); $table->string('url'); $table->unsignedInteger('sort_order')->default(1); $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('related_links'); }
};
