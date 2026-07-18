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
        Schema::create('event_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->nullable()->constrained('events')->cascadeOnDelete();
            $table->string('sub_event');
            $table->string('kebutuhan_aspek');
            $table->date('target_tanggal')->nullable();
            $table->foreignId('pj_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('mc_pengisi')->nullable();
            $table->text('perkap_utama')->nullable();
            $table->text('detail_deskripsi')->nullable();
            $table->string('status')->default('Belum Mulai');
            $table->text('catatan_plan_b')->nullable();
            $table->foreignId('last_modified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('last_modified_at')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('target_tanggal');
            $table->index('sub_event');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_tasks');
    }
};
