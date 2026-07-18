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
        Schema::create('admin_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('divisi_id')->constrained('divisis')->cascadeOnDelete();
            $table->string('nama_tugas');
            $table->date('target_tanggal')->nullable();
            $table->foreignId('pj_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status')->default('Belum Mulai');
            $table->text('catatan')->nullable();
            $table->foreignId('last_modified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('last_modified_at')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('target_tanggal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_tasks');
    }
};
