<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('media_assets', function (Blueprint $table) {
            $table->id(); $table->string('file_name'); $table->string('file_path'); $table->string('mime_type')->nullable(); $table->unsignedBigInteger('size_bytes')->default(0); $table->string('alt_text')->nullable(); $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete(); $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('media_assets'); }
};
