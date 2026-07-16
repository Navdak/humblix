<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visitor_events', function (Blueprint $table): void {
            $table->id();
            $table->string('visitor_hash', 64)->index();
            $table->string('path', 255)->index();
            $table->string('route_name', 120)->nullable()->index();
            $table->string('referrer_host', 160)->nullable()->index();
            $table->string('device_type', 24)->default('desktop')->index();
            $table->string('user_agent_hash', 64)->nullable();
            $table->timestamp('created_at')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visitor_events');
    }
};
