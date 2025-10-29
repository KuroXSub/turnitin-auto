<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->ipAddress('ip_address')->nullable();
            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');
            $table->string('original_filename');
            $table->string('file_path');
            $table->string('resolved_file_path')->nullable();
            $table->string('status')->default('pending')->index();
            $table->string('verification_code', 16)->unique()->nullable()->index();
            $table->text('admin_notes')->nullable();
            $table->timestamp('checked_at')->nullable();
            $table->foreignId('checked_by_admin_id')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
