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
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->nullable()->constrained('events')->cascadeOnDelete();
            $table->string('nama_barang');
            $table->string('divisi_peminta')->nullable();
            $table->string('sistem_pengadaan');
            $table->date('tgl_pinjam_beli')->nullable();
            $table->date('deadline_kumpul')->nullable();
            $table->date('tgl_kembali')->nullable();
            $table->foreignId('pj_peminjam_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('pihak_dipinjami')->nullable();
            $table->decimal('estimasi_biaya', 15, 2)->default(0);
            $table->string('posisi_barang')->nullable();
            $table->string('status_ceklis')->default('Tersedia');
            $table->text('catatan')->nullable();
            $table->foreignId('last_modified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('last_modified_at')->nullable();
            $table->timestamps();

            $table->index('status_ceklis');
            $table->index('deadline_kumpul');
            $table->index('sistem_pengadaan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_items');
    }
};
