<?php

use App\Models\PageHero;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_heroes', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('label');
            $table->string('eyebrow')->nullable();
            $table->string('title');
            $table->text('subtitle')->nullable();
            $table->string('image_path')->nullable();
            $table->string('fallback_image_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        PageHero::seedDefaults();
    }

    public function down(): void
    {
        Schema::dropIfExists('page_heroes');
    }
};
