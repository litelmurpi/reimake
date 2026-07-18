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
        Schema::create('consumption_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->nullable()->constrained('events')->cascadeOnDelete();
            $table->string('jenis_konsumsi');
            $table->integer('porsi_jumlah');
            $table->decimal('harga_satuan', 15, 2);
            $table->decimal('total_biaya', 15, 2)->default(0);
            $table->foreignId('pj_konsumsi_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('kru_pembantu')->nullable();
            $table->text('perlengkapan')->nullable();
            $table->string('status_kesiapan')->default('Belum Dipesan');
            $table->text('catatan_vendor')->nullable();
            $table->foreignId('last_modified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('last_modified_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consumption_items');
    }
};
